// In a Vue component, e.g., MyComponent.vue

<template>
  <div>
    <v-row justify="center" align="center">
      <v-switch
        density="compact"
        color="yellow"
        prepend-icon="mdi mdi-weather-night"
        append-icon="mdi mdi-white-balance-sunny"
        v-model="theme"
        hide-details
        inset
      ></v-switch>
    </v-row>
  </div>
</template>

<script>
import { setThemeStyle } from "../plugins/vuetify";

export default {
  name: "ToggleTheme",
  data() {
    return {
      theme: false,
      currentTheme: "dark", // Initialize with default theme
    };
  },

  watch: {
    theme(newValue) {
      this.currentTheme = newValue ? "light" : "dark";
      setThemeStyle(this.currentTheme); // Call function to update the theme in Vuetify
    },
  },
  methods: {
    // toggleTheme() {
    //   this.currentTheme = this.currentTheme === "dark" ? "light" : "dark";
    //   setThemeStyle(this.currentTheme); // Call function to update the theme in Vuetify
    // },
  },
};
</script>
