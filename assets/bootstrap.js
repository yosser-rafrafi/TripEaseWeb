// ✅ Utilise uniquement stimulus-bridge
import { startStimulusApp } from '@symfony/stimulus-bridge';
const app = startStimulusApp();

// ✅ Enregistre le contrôleur "live"
import liveController from '@symfony/ux-live-component';
app.register('live', liveController);

// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
