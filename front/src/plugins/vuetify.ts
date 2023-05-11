import { createVuetify, ThemeDefinition } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import '@mdi/font/css/materialdesignicons.css' // Ensure you are using css-loader

const myCustomLightTheme: ThemeDefinition = {
    dark: false,
    colors: {
        background: '#EAEAEA',
        surface: '#F2F2F2',
        primary: '#FF0035',
        contrast: '#FFFF00',
        'primary-darken-1': '#CC0029',
        secondary: '#38405F',
        'secondary-darken-1': '#2D344D',
        lightgray: '#e8e8e8',
        error: '#B00020',
        info: '#2196F3',
        success: '#4CAF50',
        warning: '#FB8C00',
    }
}

export default createVuetify({
    components,
    directives,
    defaults: {
        VBtn: {
            color: 'primary',
            variant: 'flat',
            rounded: 'xl'
        },
        VMenu: {
            location: 'bottom'
        },
        VCard: {
            rounded: 'xl',
            flat: true
        }
    },
    theme: {
        defaultTheme: 'myCustomLightTheme',
        themes: {
            myCustomLightTheme,
        }
    }
})