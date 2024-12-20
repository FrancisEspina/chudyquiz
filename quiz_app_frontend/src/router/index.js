// src/router/index.js
import { createRouter, createWebHistory } from "vue-router";
import Home from "../views/Home.vue";
import Questions from "../views/AddQuestionsPage.vue";
import TestPage from "../views/TestPage.vue";
import Review from "@/views/Review.vue";
const routes = [
  { path: "/:pathMatch(.*)*", redirect: "/" },
  { path: "/", name: "home", component: Home },
  { path: "/questions", name: "questions", component: Questions },
  { path: "/test", name: "test", component: TestPage },
  { path: "/review", name: "review", component: Review },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
