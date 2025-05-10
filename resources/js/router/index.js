import { createRouter, createWebHistory } from 'vue-router';
import Layout from '../views/Layouts/Layout.vue';
import Home from '../views/Home.vue';
import About from '../views/About.vue';
import Login from '../views/auth/Login.vue';
import Productos from '../views/Productos.vue';
import Users from '../views/Users.vue';
import Preguntas from '../views/Preguntas.vue';
import Usuarios from '../views/Usuarios/Usuarios.vue';
import Permisos from '../views/Usuarios/rolesPermisos/Permisos.vue';
import Roles from '../views/Usuarios/rolesPermisos/Roles.vue';
import RolesPermisos from '../views/Usuarios/rolesPermisos/RolesPermisos.vue';


const routes = [
  {
    path: '/',
    component: Layout,
    meta: { requiresAuth: true },
    children: [
      { path: '', name: 'home', component: Home },
      { path: 'about', name: 'about', component: About },
      { path: 'productos', name: 'productos', component: Productos },
      { path: 'users', name: 'users', component: Users },
      {path: 'preguntas', name: 'preguntas', component: Preguntas},
      {path: 'usuarios', name:'usuarios', component: Usuarios},
      {path: 'permisos', name:'permisos', component: Permisos, meta: { title: 'Permisos' } },
      {path: 'roles', name:'roles', component: Roles, meta: { title: 'Roles' }},
      { path: 'roles/:id/edit', name: 'rolespermisos', component: RolesPermisos, meta: { title: 'Asignar permisos' } },
      
    ]
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { guest: true }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  const defaultTitle = 'C B T';

  // Toma el meta.title del último match (el hijo más profundo)
  const nearestWithTitle = to.matched.slice().reverse().find(r => r.meta && r.meta.title);

  if (nearestWithTitle) {
    document.title = nearestWithTitle.meta.title;
  } else {
    document.title = defaultTitle;
  }

  // 🔒 Rutas protegidas
  if (to.meta.requiresAuth && !token) {
    next({ name: 'login' });

    // 🚫 Rutas solo para invitados (login, registro)
  } else if (to.meta.guest && token) {
    next({ name: 'home' });

  } else {
    next();
  }
});

export default router;
