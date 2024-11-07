// plugins/vuetify.js

// Styles
import "@mdi/font/css/materialdesignicons.css";
import "vuetify/styles";

// Composables
import { createVuetify } from "vuetify";
import { ref, computed } from "vue";

// Reactive theme style reference
const themeStyle = ref("dark"); // Default theme

// Function to toggle or set the theme
export function setThemeStyle(newStyle) {
  themeStyle.value = newStyle;
}

// Use a computed property for `defaultTheme` so it reacts to changes
const vuetify = createVuetify({
  defaults: {
    VContainer: {
      style: { "margin-top": "25px" },
    },
    VRow: {
      style: { "margin-bottom": "-20px" },
    },
    VTextField: {
      variant: "outlined",
      rounded: "xl",
      density: "comfortable",
      style: { "margin-bottom": "5px" },
    },
    VBtn: {
      rounded: "xl",
      variant: "flat",
      color: "blue",
      style: { fontSize: "10" },
    },
    VCard: {
      rounded: "xl",
      variant: "flat",
    },
  },
  theme: {
    defaultTheme: computed(() => themeStyle.value),
    themes: {
      light: {
        /* customizations for light theme */
      },
      dark: {
        /* customizations for dark theme */
      },
    },
  },
});

export default vuetify;
