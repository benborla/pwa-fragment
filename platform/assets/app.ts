import './registerServiceWorker';
import './firebase_init';
  import { createApp, defineAsyncComponent } from 'vue'

const { brandcode, component, type } = <DOMStringMap>document.getElementById('pwa-app').dataset || {}

/** do not loaded vue if component is empty **/
if (component !== '') {
  createApp({
    data () {
      return {
        brandcode,
        component,
        type
      }
    },
    components: {
      LoadComponent: defineAsyncComponent(() => component && import(`./brands/${brandcode}/${type}/${component}`))
    },
    template: '<LoadComponent />'
  }).mount('#pwa-app')
}
