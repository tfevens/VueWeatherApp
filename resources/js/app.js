require('./bootstrap');

import { createApp } from 'vue'
import WeatherComponent  from './components/WeatherComponent.vue'

createApp({
    components: {
        WeatherComponent        
    }

}).mount('#app')