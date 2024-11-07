<template>
  <v-app>
    <v-app-bar elevation="0">
      <template v-slot:prepend>
        <div class="pa-5">
          <router-link
            class="text-decoration-none"
            :style="{ color: 'inherit' }"
            to="/"
          >
            <div>
              <v-icon size="40">mdi mdi-cat</v-icon>
            </div>
          </router-link>
        </div>
      </template>

      <template v-slot:append>
        <div class="pa-5">
          <ToggleTheme />
        </div>
      </template>
    </v-app-bar>
    <v-main>
      <router-view></router-view>
    </v-main>
    <AppFooter />
  </v-app>
</template>

<script>
//
</script>
