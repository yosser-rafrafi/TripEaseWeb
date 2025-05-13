// forum.js
const ForumHandler = {
    init(config) {
        this.config = config; // Store configuration
        this.initializeEventListeners();
        console.log('Main script loaded');
    },

    initializeEventListeners() {
        document.addEventListener('DOMContentLoaded', () => {
            this.setupMediaHandling();
            this.setupEditPosts();
            this.setupReactions();
        });
    },

    setupMediaHandling() {
        console.log('DOM loaded');
        // Get all required elements
        const mediaInput = document.getElementById('mediaInput');
        const selectedFileName = document.getElementById('selectedFileName');
        const mediaPreview = document.getElementById('mediaPreview');
        const imagePreview = document.getElementById('imagePreview');
        const videoPreview = document.getElementById('videoPreview');
        const cancelButton = document.getElementById('cancelPost');

        console.log('Elements:', {
            mediaInput,
            selectedFileName,
            mediaPreview,
            imagePreview,
            videoPreview,
            cancelButton
        });

        // Add file input change event
        if (mediaInput) {
            console.log('Adding change event to mediaInput');
            mediaInput.addEventListener('change', function(e) {
                console.log('File input changed');
                const file = this.files[0];
                console.log('Selected file:', file);

                if (file) {
                    selectedFileName.textContent = file.name;
                    mediaPreview.style.display = 'block';
                    if (file.type.startsWith('image/')) {
                        console.log('Image file detected');
                        imagePreview.style.display = 'block';
                        videoPreview.style.display = 'none';
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            console.log('Image loaded');
                            imagePreview.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    } else if (file.type.startsWith('video/')) {
                        console.log('Video file detected');
                        imagePreview.style.display = 'none';
                        videoPreview.style.display = 'block';
                        videoPreview.src = URL.createObjectURL(file);
                    }
                } else {
                    console.log('No file selected');
                    selectedFileName.textContent = '';
                    mediaPreview.style.display = 'none';
                }
            });
        }

        // Add cancel button click event
        if (cancelButton) {
            console.log('Adding click event to cancelButton');
            cancelButton.addEventListener('click', () => {
                console.log('Cancel button clicked');
                this.removeMedia();
                document.getElementById('postContent').value = '';
            });
        }
    },

    setupEditPosts() {
        document.querySelectorAll('.edit-post').forEach(button => {
            button.addEventListener('click', (e) => this.handleEditPost(e));
        });
    },

    setupReactions() {
        document.querySelectorAll('[data-action]').forEach(button => {
            button.addEventListener('click', (e) => this.handleReaction(e));
        });
    },

    handleEditPost(e) {
        const button = e.currentTarget;
        const postId = button.dataset.postId;
        const postCard = document.getElementById(`post-${postId}`);
        const postContent = postCard.querySelector('.post-content');
        const postText = postCard.querySelector('.post-text').innerHTML; // Get HTML content
        
        // Create the edit form
        const editForm = document.createElement('form');
        editForm.className = 'edit-post-form';
        
        // Create a unique ID for the textarea
        const textareaId = `edit-post-${postId}`;
        
        // Create the form HTML
        editForm.innerHTML = `
            <div class="form-group mb-3">
                <textarea id="${textareaId}" name="contenu">${postText}</textarea>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-primary btn-sm rounded-pill">Save</button>
                <button type="button" class="btn btn-secondary btn-sm rounded-pill cancel-edit">Cancel</button>
            </div>
        `;
    
        // Replace content with form
        postContent.innerHTML = '';
        postContent.appendChild(editForm);
    
        // Initialize CKEditor on the textarea
        CKEDITOR.replace(textareaId, {
            toolbar: [
                {
                    name: 'basicstyles',
                    items: ['Bold', 'Italic', 'Underline', 'FontSize', 'Font', 'TextColor']
                },
                {
                    name: 'paragraph',
                    items: ['NumberedList', 'BulletedList']
                },
                {
                    name: 'insert',
                    items: ['Image', 'Table']
                },
                {
                    name: 'styles',
                    items: ['Format']
                }
            ],
            font_names: 'Arial;Times New Roman;Verdana;Comic Sans MS',
            height: 200
        });
    
        // Setup form handlers with CKEditor support
        this.setupEditFormHandlers(editForm, postId, postContent, postText, textareaId);
    },
    
    setupEditFormHandlers(editForm, postId, postContent, originalText, textareaId) {
        editForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData();
            
            // Get content from CKEditor
            const editorContent = CKEDITOR.instances[textareaId].getData();
            formData.append('contenu', editorContent);
            formData.append('_token', this.config.editToken);
    
            try {
                const response = await fetch(`/forum/statut/${postId}/edit`, {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                
                if (data.success) {
                    // Destroy CKEditor instance before updating content
                    CKEDITOR.instances[textareaId].destroy();
                    postContent.innerHTML = this.getUpdatedContentHtml(data);
                    this.showFlashMessage('success', 'Post updated successfully!');
                } else {
                    this.showFlashMessage('error', data.message || 'Error updating post');
                }
            } catch (error) {
                console.error('Error:', error);
                this.showFlashMessage('error', 'Error updating post');
            }
        });
    
        // Cancel edit handler
        editForm.querySelector('.cancel-edit').addEventListener('click', () => {
            // Destroy CKEditor instance before restoring content
            CKEDITOR.instances[textareaId].destroy();
            postContent.innerHTML = `<div class="post-text">${originalText}</div>`;
        });
    }
    ,
    
    getEditFormHtml(postId, postText) {
        return `
            <div class="form-group mb-3">
                <textarea class="form-control" name="contenu" rows="3">${postText}</textarea>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary btn-sm cancel-edit">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
            </div>
        `;
    },
    
 
    async handleEditSubmit(e, postId, postContent) {
        e.preventDefault();
        const formData = new FormData(e.target);
        formData.append('_token', this.config.editToken);

        try {
            const response = await fetch(`/forum/statut/${postId}/edit`, {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            
            if (data.success) {
                postContent.innerHTML = this.getUpdatedContentHtml(data);
                this.showFlashMessage('success', 'Post updated successfully!');
            } else {
                this.showFlashMessage('error', data.message || 'Error updating post');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showFlashMessage('error', 'Error updating post');
        }
    },

    async handleRemoveMedia(postId, editForm) {
        if (!confirm('Are you sure you want to remove the media?')) return;

        try {
            const response = await fetch(`/forum/statut/${postId}/remove-media`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.config.removeMediaToken
                }
            });
            const data = await response.json();

            if (data.success) {
                editForm.querySelector('.current-media').remove();
                editForm.querySelector('.remove-media').remove();
                this.showFlashMessage('success', 'Media removed successfully!');
            } else {
                this.showFlashMessage('error', data.message || 'Error removing media');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showFlashMessage('error', 'Error removing media');
        }
    },

    async handleReaction(e) {
        e.preventDefault();
        const button = e.currentTarget;
        const { action, statutId } = button.dataset;
        const url = `/forum/statut/statut/${statutId}/${action}`;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            this.updateReactionUI(button, data);
        } catch (error) {
            console.error('Error:', error);
        }
    },
    updateReactionUI(button, data) {
        const buttonsContainer = button.closest('.d-flex');
    
        const likeButton = buttonsContainer.querySelector('[data-action="like"]');
        const dislikeButton = buttonsContainer.querySelector('[data-action="dislike"]');
    
        // Remove all previous classes
        likeButton.className = 'btn btn-outline-secondary reaction-button rounded-pill';
        dislikeButton.className = 'btn btn-outline-secondary reaction-button rounded-pill';
    
        // Now if user reacted, fill the corresponding one
        if (data.userReaction === 'LIKE') {
            likeButton.classList.remove('btn-outline-secondary');
            likeButton.classList.add('btn-secondary');
            this.animateButton(likeButton);
        } else if (data.userReaction === 'DISLIKE') {
            dislikeButton.classList.remove('btn-outline-secondary');
            dislikeButton.classList.add('btn-secondary');
            this.animateButton(dislikeButton);
        }
    
        // Update like and dislike counters
        buttonsContainer.querySelector('.like-count').textContent = data.likeCount;
        buttonsContainer.querySelector('.dislike-count').textContent = data.dislikeCount;
    },
    

    removeMedia() {
        console.log('Removing media');
        const mediaInput = document.getElementById('mediaInput');
        const selectedFileName = document.getElementById('selectedFileName');
        const mediaPreview = document.getElementById('mediaPreview');
        const imagePreview = document.getElementById('imagePreview');
        const videoPreview = document.getElementById('videoPreview');

        mediaInput.value = '';
        selectedFileName.textContent = '';
        mediaPreview.style.display = 'none';
        imagePreview.style.display = 'none';
        videoPreview.style.display = 'none';
        if (videoPreview.src) {
            URL.revokeObjectURL(videoPreview.src);
        }
    },

    showFlashMessage(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} mt-3 text-center`;
        alertDiv.textContent = message;
        document.querySelector('.container').insertBefore(alertDiv, document.querySelector('.row'));
        setTimeout(() => alertDiv.remove(), 5000);
    },

    getUpdatedContentHtml(data) {
        return `
            <p class="post-text">${data.contenu}</p>
            ${data.mediaUrl ? `
            ` : ''}
        `;
    },
    animateButton(button) {
        button.classList.add('animate-pop');
    
        // Remove the animation class after animation ends
        setTimeout(() => {
            button.classList.remove('animate-pop');
        }, 300); // Match with CSS animation duration
    }
    
};

// Export the handler
window.ForumHandler = ForumHandler;