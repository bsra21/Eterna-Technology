import { createRouter, createWebHistory } from "vue-router";

import Login from "../pages/auth/Login.vue";
import Dashboard from "../pages/dashboard/Dashboard.vue";
import PostList from "../pages/posts/PostList.vue";
import CategoryList from "../pages/categories/CategoryList.vue";
import DashboardLayout from "../layouts/DashboardLayout.vue";
import BlogLayout from "../layouts/BlogLayout.vue";
import CommentList from "../pages/comments/CommentList.vue";
import BlogPage from "../pages/blog/BlogPage.vue";
import PostDetail from "../pages/blog/PostDetail.vue";
const routes = [
    {
        path: "/",
        component: BlogLayout,
        // component: BlogPage,
        children: [
            {
                path: "/",
                component: BlogPage,
            },
            {
                path: "/post/:id",
                component: PostDetail,
            },
            {
                path: "admin/posts",
                component: PostList,
                meta: { requiresEditor: true }, // sadece admin
            },
            {
                path: "admin/comments",
                component: CommentList,
                meta: { requiresEditor: true }, // admin + editor
            },
            {
                path: "categories",
                component: CategoryList,
                meta: { requiresAdmin: true }, // sadece admin
            },
        ],
    },

    /* {
        path: "/login",
        name: "Login",
        component: Login,
    },*/

    /*  {
        path: "/dashboard",
        component: DashboardLayout,
        meta: { requiresAuth: true },
        children: [
            {
                path: "",
                component: Dashboard,
            },
            {
                path: "posts",
                component: PostList,
                meta: { requiresAdmin: true }, // sadece admin
            },
            {
                path: "admin/comments",
                component: CommentList,
                meta: { requiresEditor: true }, // admin + editor
            },
            {
                path: "categories",
                component: CategoryList,
                meta: { requiresAdmin: true }, // sadece admin
            },
        ],
    }, */
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Auth Guard
// Auth & Role Based Guard
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem("token");
    const role = localStorage.getItem("role");

    if (to.meta.requiresAuth && !token) {
        return next("/login");
    }

    if (to.meta.role && role !== to.meta.role) {
        return next("/dashboard");
    }

    next();
});

export default router;
