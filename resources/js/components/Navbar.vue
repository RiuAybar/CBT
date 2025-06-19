<template>
    <nav class="navbar navbar-expand navbar-light navbar-bg">
        <a class="sidebar-toggle js-sidebar-toggle" @click="collapseMenu">
            <i class="hamburger align-self-center"></i>
        </a>

        <form class="d-none d-sm-inline-block">
            <div class="input-group input-group-navbar">
                <input name="Search" type="text" class="form-control" placeholder="Search…" aria-label="Search">
                <button class="btn" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-search align-middle">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </div>
        </form>

        <ul class="navbar-nav d-none d-lg-flex">
            <li class="nav-item px-2 dropdown">
                <a class="nav-link dropdown-toggle" href="https://demo.adminkit.io/ui-modals#" id="megaDropdown"
                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Mega Menu
                </a>
                <div class="dropdown-menu dropdown-menu-start dropdown-mega" aria-labelledby="megaDropdown">
                    <div class="d-md-flex align-items-start justify-content-start">
                        <div class="dropdown-mega-list">
                            <div class="dropdown-header">UI Elements</div>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">Alerts</a>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">Buttons</a>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">Cards</a>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">Carousel</a>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">General</a>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">Grid</a>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">Modals</a>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">Tabs</a>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">Typography</a>
                        </div>
                        <div class="dropdown-mega-list">
                            <div class="dropdown-header">Forms</div>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">Layouts</a>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">Basic Inputs</a>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">Input Groups</a>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">Advanced Inputs</a>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">Editors</a>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">Validation</a>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">Wizard</a>
                        </div>
                        <div class="dropdown-mega-list">
                            <div class="dropdown-header">Tables</div>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">Basic Tables</a>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">Responsive Table</a>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">Table with Buttons</a>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">Column Search</a>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">Muulti Selection</a>
                            <a class="dropdown-item" href="https://demo.adminkit.io/ui-modals#">Ajax Sourced Data</a>
                        </div>
                    </div>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="https://demo.adminkit.io/ui-modals#" id="resourcesDropdown"
                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Resources
                </a>
                <div class="dropdown-menu" aria-labelledby="resourcesDropdown">
                    <a class="dropdown-item" href="https://adminkit.io/" target="_blank"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-home align-middle me-1">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        Homepage</a>
                    <a class="dropdown-item" href="https://adminkit.io/docs/" target="_blank"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-book-open align-middle me-1">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                        </svg>
                        Documentation</a>
                    <a class="dropdown-item" href="https://adminkit.io/docs/getting-started/changelog/"
                        target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-edit align-middle me-1">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg> Changelog</a>
                </div>
            </li>
        </ul>

        <div class="navbar-collapse collapse">
            <ul class="navbar-nav navbar-align">
                <li class="nav-item dropdown">
                    <v-select name="año" input-id="año" :options="years" :modelValue="selectedYear" @update:modelValue="changeYear" label="Año"
                        placeholder="Selecciona un año" />
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                        <img :src="getUser?.avatar_url || `/storage/avatars/Def/avatar.jpg`"
                            class="avatar img-fluid rounded me-1" :alt="getUser?.name" />
                    </a>

                    <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                        <img :src="getUser?.avatar_url || `/storage/avatars/Def/avatar.jpg`"
                            class="avatar img-fluid rounded me-1" :alt="getUser?.name" />
                        <span class="text-dark">{{ getUser?.user?.name || getUser?.name }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <router-link class="dropdown-item" to="/perfil">
                            <i class="align-middle me-1" data-feather="user"></i>
                            Perfil
                        </router-link>
                        <a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="pie-chart"></i>
                            Analytics</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="index.html"><i class="align-middle me-1"
                                data-feather="settings"></i> Settings & Privacy</a>
                        <a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="help-circle"></i>
                            Help Center</a>
                        <div class="dropdown-divider"></div>
                        <button @click="logout" class="dropdown-item"> <i class="bi bi-box-arrow-right"></i> Cerrar
                            sesión</button>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

import { mapGetters, mapActions } from 'vuex';
import api, { setAuthToken } from '../services/api';

export default {
    name: 'Navbar',
    components: {
        vSelect
    },
    data() {
        const currentYear = new Date().getFullYear();
        return {
            isCollapsed: localStorage.getItem('isCollapsed') === 'true',
            user: {},
            years: []
        };
    },
    watch: {
        getUser: {
            handler(newVal) {
                if (newVal) {
                    this.user = { ...newVal };
                }
            },
            immediate: true,
        },
        selectedYear: {
            immediate: true,
            handler(newYear) {
                this.years = this.generateSurroundingYears(Number(newYear));
            }
        }
    },
    methods: {
        async logout() {
            await api.post('/logout');
            localStorage.removeItem('token');   // 🧹 Borra el token
            setAuthToken(null);                 // ❌ Quita el token de los headers
            this.$router.push({ name: 'login' }); // 🔁 Redirige a login
        },
        ...mapActions(['toggleCollapsed', 'setSelectedYear']),
        collapseMenu() {
            this.toggleCollapsed();
        },
        generateSurroundingYears(centerYear) {
            const years = [];
            for (let i = centerYear - 2; i <= centerYear + 2; i++) {
                years.push(i);
            }
            return years;
        },
        changeYear(year) {
            const validYear = typeof year === 'number' && !isNaN(year) ? year : new Date().getFullYear();
            this.setSelectedYear(validYear);
        }
    },
    computed: {
        ...mapGetters('auth', ['isAuthenticated', 'getUser']),
        ...mapGetters(['selectedYear'])
    },
}
</script>