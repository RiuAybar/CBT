import { createRouter, createWebHistory } from 'vue-router';
import store from '../store';
// import Layouts from '../views/Layouts/Layout.vue';
import Home from '../views/Home.vue';
// import About from '../views/About.vue';
import Users from '../views/Usuarios/Users.vue';
// import Usuarios from '../views/Usuarios/Usuarios.vue';
import Permisos from '../views/Usuarios/rolesPermisos/Permisos.vue';
import Roles from '../views/Usuarios/rolesPermisos/Roles.vue';
import RolesPermisos from '../views/Usuarios/rolesPermisos/RolesPermisos.vue';
import Login from '../views/auth/Login.vue';
import RegistrarPassword from '../views/auth/RegistrarPassword.vue';
import RestablecerPassword from '../views/auth/RestablecerPassword.vue';
import Grados from '../views/Logistica/Grados/Grados.vue';
import Grupos from '../views/Logistica/Grados/Grupos.vue';
import Materias from '../views/Logistica/Grados/Materias.vue';
import Semestres from '../views/Logistica/Grados/Semestres.vue';
import Carreras from '../views/Logistica/Grados/Carreras.vue';
import HorasDocente from '../views/Logistica/Grados/HorasDocente.vue';
import Parciales from '../views/Logistica/Grados/Parciales.vue';
import Seguimiento from '../views/Logistica/Seguimiento/Seguimiento.vue';
import ConfiguracionUsuarios from '../views/Usuarios/Configuracion/ConfiguracionUsuarios.vue';
import Lista from '../views/Logistica/Listas/Lista.vue';
import Calificaciones from '../views/Logistica/Calificaciones/Calificaciones.vue';
import ReportePDF from '../views/Reportes/ReporteEjemplo/ReportePDF.vue';

const routes = [
  {
    path: '/login',
    component: () => import('../views/Layouts/auth/Auth.vue'),
    meta: { guest: true },
    children: [
      { path: '', name: 'login', component: Login, meta: { title: 'Inicio' }, meta: { guest: true }  },
      { path: '/restablecer/contrasena', name: 'restablecerContraseña', component: RestablecerPassword, meta: { title: 'Restablecer Contraseña' }, meta: { guest: true }  },
      {
        path: 'registrar/password/:token', // ✅ sin "/"
        name: 'registrar-password',
        component: RegistrarPassword,
        props: route => ({
          token: route.params.token,
          email: route.query.email
        }),
        meta: { guest: true } 
      }
    ],
  },
  {
    path: '/',
    component: () => import('../views/Layouts/Layout.vue'),
    meta: { requiresAuth: true },
    children: [
      { path: '', name: 'home', component: Home, meta: { title: 'Inicio' } },
      // { path: 'about', name: 'about', component: About, meta: { title: 'Sobre' } },
      { path: 'calificaciones', name: 'calificaciones', component: Calificaciones, meta: { title: 'Calificaciones' } },
      { path: 'users', name: 'users', component: Users, meta: { title: 'Usuarios', requiresPermission: 'ver usuarios' } },
      // { path: 'usuarios', name: 'usuarios', component: Usuarios, meta: { title: 'Usuarios' } },
      { path: 'permisos', name: 'permisos', component: Permisos, meta: { title: 'Permisos', requiresPermission: 'ver permisos' } },
      { path: 'roles', name: 'roles', component: Roles, meta: { title: 'Roles', requiresPermission: 'ver roles' } },
      { path: 'roles/:id/edit', name: 'rolespermisos', component: RolesPermisos, meta: { title: 'Asignar permisos', requiresPermission: 'ver permisos asignados a roles' } },
      { path: 'grados', name: 'grados', component: Grados, meta: { title: 'Lista de Grados.', requiresPermission: 'ver grados' } },
      { path: 'grupos/:id/edit', name: 'grupos', component: Grupos, meta: { title: 'Lista de grupos.', requiresPermission: 'ver grupos' } },
      { path: 'lista/:id/edit', name: 'lista', component: Lista, meta: { title: 'Lista de lista.', requiresPermission: 'ver listas' } },
      { path: 'semestres', name: 'semestres', component: Semestres, meta: { title: 'Lista de semestres.', requiresPermission: 'ver semestres' } },
      { path: 'materias', name: 'materias', component: Materias, meta: { title: 'Lista de materias.', requiresPermission: 'ver materias' } },
      { path: 'carreras', name: 'carreras', component: Carreras, meta: { title: 'Lista de carreras.', requiresPermission: 'ver carreras' } },
      { path: 'horasdocente', name: 'horasdocente', component: HorasDocente, meta: { title: 'Registro de meses y horas.', requiresPermission: 'ver horas docente' } },
      { path: 'parciales', name: 'parciales', component: Parciales, meta: { title: 'Lista de parciales.', requiresPermission: 'ver parciales' } },
      { path: 'seguimiento', name: 'seguimiento', component: Seguimiento, meta: { title: 'Lista de seguimiento.', requiresPermission: 'ver seguimiento' } },
      { path: 'perfil', name: 'perfil', component: ConfiguracionUsuarios, meta: { title: 'Configuración de mi perfil.' } },
      { path: 'reportepdf', name: 'reportepdf', component: ReportePDF, meta: { title: 'Configuración de mi ReportePDF.', requiresPermission: 'ver f1' } },
    ]
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to, from, next) => {
  const token = localStorage.getItem('token');
  const defaultTitle = 'C B T';

  const nearestWithTitle = to.matched.slice().reverse().find(r => r.meta?.title);
  document.title = nearestWithTitle ? `${defaultTitle} | ${nearestWithTitle.meta.title}` : defaultTitle;

  // ============================
  // 1️⃣ Validación de LOGIN
  // ============================
  if (to.meta.requiresAuth && !token) {
    return next({ name: 'login' });
  }

  if (to.meta.guest && token) {
    return next({ name: 'home' });
  }

  // ============================
  // 2️⃣ Esperar a que Vuex cargue usuario si no está cargado
  // ============================
  if (token && !store.getters['auth/getUser']) {
    await store.dispatch('auth/fetchUser');
  }

  const user = store.getters['auth/getUser'];

  // Si la ruta requiere autenticación pero no hay user cargado
  if (to.meta.requiresAuth && !user) {
    return next({ name: 'login' });
  }

  // ============================
  // 3️⃣ Validar ROLES
  // ============================
  if (to.meta.requiresRole) {
    if (user.role !== to.meta.requiresRole) {
      console.warn('🚫 No tienes el rol requerido');
      return next({ name: 'home' });
    }
  }

  // ============================
  // 4️⃣ Validar PERMISOS
  // ============================
  if (to.meta.requiresPermission) {
    const hasPermission = user.permissions?.includes(to.meta.requiresPermission);

    if (!hasPermission) {
      console.warn('🚫 No tienes el permiso requerido:', to.meta.requiresPermission);
      return next({ name: 'home' });
    }
  }

  next();
});

// router.beforeEach(async (to, from, next) => {
//   const token = localStorage.getItem('token');
//   const defaultTitle = 'C B T';

//   // Toma el meta.title del último match (el hijo más profundo)
//   const nearestWithTitle = to.matched.slice().reverse().find(r => r.meta && r.meta.title);

//   if (nearestWithTitle) {
//     document.title = `${defaultTitle} | ${nearestWithTitle.meta.title}`;
//   } else {
//     document.title = defaultTitle;
//   }

//   // 🔒 Rutas protegidas
//   if (to.meta.requiresAuth && !token) {
//     // console.log(1);
//     next({ name: 'login' });
//     // 🚫 Rutas solo para invitados (login, registro)
//   } else if (to.meta.guest && token) {
//     // console.log(2, to.meta.guest , token);
//     next({ name: 'home' });

//   } else {
//     // console.log(3);
//     next();
//   }
// });

export default router;
