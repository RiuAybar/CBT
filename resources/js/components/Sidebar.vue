<template>
	<nav id="sidebar" class="sidebar js-sidebar" :class="{ collapsed: isCollapsed }">
		<div class="sidebar-content js-simplebar">
			<router-link to="/" class='sidebar-brand'>
				<span class="sidebar-brand-text align-middle">
					C B T
					<sup><small class="badge bg-primary text-uppercase">Instituto de Estudios</small></sup>
				</span>
			</router-link>

			<ul class="sidebar-nav">
				<li class="sidebar-header">
					Pages
				</li>
				<li class="sidebar-item" :class="{ active: isActive(['/permisos', '/roles', `/roles/${this.$route.params.id}/edit`]) }">
					<a data-bs-target="#dashboards" data-bs-toggle="collapse" class="sidebar-link collapsed">
						<i class="bi bi-person-gear me-2"></i>
						<span class="align-middle">
							Roles y Permisos
						</span>
					</a>
					<ul id="dashboards" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
						<li class="sidebar-item" :class="{ active: isActive('/permisos') }">
							<router-link to="/permisos" class='sidebar-link'>Permisos</router-link>
						</li>
						<li class="sidebar-item" :class="{ active: isActive(['/roles',`/roles/${this.$route.params.id}/edit`]) }">
							<router-link to="/roles" class='sidebar-link'>Roles</router-link>
						</li>
						<!-- <li class="sidebar-item">
							<a class='sidebar-link' href='/dashboard-ecommerce'>E-Commerce <span
									class="sidebar-badge badge bg-primary">Pro</span></a>
						</li>
						<li class="sidebar-item">
							<a class='sidebar-link' href='/dashboard-crypto'>Crypto <span
									class="sidebar-badge badge bg-primary">Pro</span></a>
						</li> -->
					</ul>
				</li>

				<li class="sidebar-item" :class="{ active: isActive(['/users']) }">
					<a data-bs-target="#dashboardsUser" data-bs-toggle="collapse" class="sidebar-link collapsed">
						<i class="bi bi-person-circle me-2"></i>
						<span class="align-middle">
							Usuarios
						</span>
					</a>
					<ul id="dashboardsUser" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
						<li class="sidebar-item" :class="{ active: isActive('/users') }">
							<router-link to="/users" class='sidebar-link'>Usuarios</router-link>
						</li>
						<!-- <li class="sidebar-item" :class="{ active: isActive(['/roles',`/roles/${this.$route.params.id}/edit`]) }">
							<router-link to="/roles" class='sidebar-link'>Roles</router-link>
						</li> -->
					</ul>
				</li>

				<li class="sidebar-item" :class="{ active: isActive(['/permisos', '/roles', `/roles/${this.$route.params.id}/edit`]) }">
					<!-- <a data-bs-target="#dashboardsLista" data-bs-toggle="collapse" class="sidebar-link collapsed">
						<i class="bi bi-person-circle me-2"></i>
						<span class="align-middle">
							Listas
						</span>
					</a>
					<ul id="dashboardsLista" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
						<li class="sidebar-item" :class="{ active: isActive('/lista') }">
							<router-link to="/lista" class='sidebar-link'>Lista</router-link>
						</li>
						<li class="sidebar-item" :class="{ active: isActive(['/roles',`/roles/${this.$route.params.id}/edit`]) }">
							<router-link to="/roles" class='sidebar-link'>Roles</router-link>
						</li>
					</ul> -->
				</li>

				<li class="sidebar-header">
					
				</li>
				<li class="sidebar-item" :class="{ active: isActive(['/grados', '/grupos', '/materias']) }">
					<a data-bs-target="#componentes" data-bs-toggle="collapse" class="sidebar-link collapsed">
						<i class="bi bi-gear-fill me-2"></i>
						<span class="align-middle">
							Configuraciones
						</span>
					</a>
					<ul id="componentes" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
						<li class="sidebar-item" :class="{ active: isActive('/grados') }">
							<router-link to="/grados" class='sidebar-link'>Grados</router-link>
						</li>
						<li class="sidebar-item" :class="{ active: isActive(['/grupos']) }">
							<router-link to="/grupos" class='sidebar-link'>Grupos</router-link>
						</li>
						<li class="sidebar-item" :class="{ active: isActive(['/materias']) }">
							<router-link to="/materias" class='sidebar-link'>Materias</router-link>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>



</template>

<script>
import { mapGetters } from 'vuex';

export default {
	name: 'Sidebar',
	data() {
		return {

		}
	},
	computed: {
		...mapGetters(['isCollapsed'])
	},
	methods: {
		isActive(path) {
			if (Array.isArray(path)) {
				return path.includes(this.$route.path);
			}
			return this.$route.path === path;
		}
	}
}

</script>

<style></style>