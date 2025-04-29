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
        const postText = postCard.querySelector('.post-text');
        const postMedia = postCard.querySelector('.post-media');

        const editForm = document.createElement('form');
        editForm.className = 'edit-post-form';
        editForm.innerHTML = this.getEditFormHtml(postId, postText, postMedia);

        postContent.innerHTML = '';
        postContent.appendChild(editForm);

        this.setupEditFormHandlers(editForm, postId, postContent, postText, postMedia);
    },

    getEditFormHtml(postId, postText, postMedia) {
        return `
            <div class="form-group mb-3">
                <textarea class="form-control" name="contenu" rows="3">${postText.textContent}</textarea>
            </div>
            <div class="media-actions mb-3">
                ${postMedia ? `
                    <div class="current-media mb-2">
                        ${postMedia.innerHTML}
                    </div>
                    <button type="button" class="btn btn-danger btn-sm remove-media" data-post-id="${postId}">
                        <i class="fas fa-trash"></i> Remove Media
                    </button>
                ` : ''}
            </div>
            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary btn-sm cancel-edit">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
            </div>
        `;
    },

    setupEditFormHandlers(editForm, postId, postContent, postText, postMedia) {
        editForm.addEventListener('submit', (e) => this.handleEditSubmit(e, postId, postContent));
        
        editForm.querySelector('.cancel-edit').addEventListener('click', () => {
            postContent.innerHTML = `
                <p class="post-text">${postText.textContent}</p>
                ${postMedia ? postMedia.outerHTML : ''}
            `;
        });

        if (postMedia) {
            const removeMediaBtn = editForm.querySelector('.remove-media');
            if (removeMediaBtn) {
                removeMediaBtn.addEventListener('click', () => this.handleRemoveMedia(postId, editForm));
            }
        }
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
                <div class="text-center post-media">
                    ${data.typeContenu === 'image' 
                        ? `<img src="${data.mediaUrl}" class="img-fluid rounded mb-3" style="max-height: 500px;" alt="Post image">`
                        : `<video controls class="w-100 rounded mb-3" style="max-height: 500px;">
                            <source src="${data.mediaUrl}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>`
                    }
                </div>
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