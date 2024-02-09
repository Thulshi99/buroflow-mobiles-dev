import Alpine from 'alpinejs';
import Tooltip from '@ryangjchandler/alpine-tooltip';
import FormsAlpinePlugin from '../../vendor/filament/forms/dist/module.esm';
import Focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';
import persist from '@alpinejs/persist';
import AlpineFloatingUI from '@awcodes/alpine-floating-ui'
import NotificationsAlpinePlugin from '../../vendor/filament/notifications/dist/module.esm'

Alpine.plugin(AlpineFloatingUI)
Alpine.plugin(NotificationsAlpinePlugin)

Alpine.plugin(FormsAlpinePlugin)
Alpine.plugin(Focus);
Alpine.plugin(collapse);
Alpine.plugin(persist);
Alpine.plugin(Tooltip);

window.Alpine = Alpine
Alpine.start()
