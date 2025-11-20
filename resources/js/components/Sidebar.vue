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
				<li class="sidebar-header" v-if="getUserRole == 'estudiante'">
					Calificaciones
				</li>
				<li class="sidebar-item" :class="{ active: isActive('/calificaciones') }" v-if="getUserRole == 'estudiante'">
					<router-link class="sidebar-link" to="/calificaciones">
						<span class="align-middle">
							Calificaciones
						</span>
					</router-link>
				</li>

				<li class="sidebar-header"
					v-if="hasPermission('ver roles') || hasPermission('ver permisos') || hasPermission('ver usuarios')">
					Pages
				</li>
				<li class="sidebar-item" v-if="hasPermission('ver roles') || hasPermission('ver permisos')"
					:class="{ active: isActive(['/permisos', '/roles', `/roles/${this.$route.params.id}/edit`]) }">
					<a data-bs-target="#dashboards" data-bs-toggle="collapse" class="sidebar-link collapsed">
						<i class="bi bi-person-gear me-2"></i>
						<span class="align-middle">
							Roles y Permisos
						</span>
					</a>
					<ul id="dashboards" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
						<li class="sidebar-item" :class="{ active: isActive('/permisos') }"
							v-if="hasPermission('ver permisos')">
							<router-link to="/permisos" class='sidebar-link'>Permisos</router-link>
						</li>
						<li class="sidebar-item"
							:class="{ active: isActive(['/roles', `/roles/${this.$route.params.id}/edit`]) }"
							v-if="hasPermission('ver roles')">
							<router-link to="/roles" class='sidebar-link'>Roles</router-link>
						</li>
					</ul>
				</li>

				<li class="sidebar-item" v-if="hasPermission('ver usuarios')" :class="{ active: isActive(['/users']) }">
					<a data-bs-target="#dashboardsUser" data-bs-toggle="collapse" class="sidebar-link collapsed">
						<i class="bi bi-person-circle me-2"></i>
						<span class="align-middle">
							Usuarios
						</span>
					</a>
					<ul id="dashboardsUser" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar"
						v-if="hasPermission('ver usuarios')">
						<li class="sidebar-item" :class="{ active: isActive('/users') }">
							<router-link to="/users" class='sidebar-link'>Usuarios</router-link>
						</li>
					</ul>
				</li>
				<li class="sidebar-header" v-if="
					hasPermission('ver grados') ||
					hasPermission('ver materias') ||
					hasPermission('ver semestres') ||
					hasPermission('ver carreras') ||
					hasPermission('ver horas docente') ||
					hasPermission('ver parciales')
				">
					conf
				</li>
				<li class="sidebar-item" v-if="
					hasPermission('ver grados') ||
					hasPermission('ver materias') ||
					hasPermission('ver semestres') ||
					hasPermission('ver carreras') ||
					hasPermission('ver horas docente') ||
					hasPermission('ver parciales')
				" :class="{ active: isActive(['/grados', '/grupos', '/materias', '/semestres', '/carreras', '/parciales', `/grupos/${this.$route.params.id}/edit`]) }">
					<a data-bs-target="#componentes" data-bs-toggle="collapse" class="sidebar-link collapsed">
						<i class="bi bi-gear-fill me-2"></i>
						<span class="align-middle">
							Configuraciones
						</span>
					</a>
					<ul id="componentes" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
						<li class="sidebar-item" v-if="hasPermission('ver grados')"
							:class="{ active: isActive(['/grados', `/grupos/${this.$route.params.id}/edit`]) }">
							<router-link to="/grados" class='sidebar-link'>Grados</router-link>
						</li>
						<li class="sidebar-item" v-if="hasPermission('ver materias')"
							:class="{ active: isActive(['/materias']) }">
							<router-link to="/materias" class='sidebar-link'>Materias</router-link>
						</li>
						<li class="sidebar-item" v-if="hasPermission('ver semestres')"
							:class="{ active: isActive(['/semestres']) }">
							<router-link to="/semestres" class='sidebar-link'>Semestres</router-link>
						</li>
						<li class="sidebar-item" v-if="hasPermission('ver carreras')"
							:class="{ active: isActive(['/carreras']) }">
							<router-link to="/carreras" class='sidebar-link'>Carreras</router-link>
						</li>
						<li class="sidebar-item" v-if="hasPermission('ver horas docente')"
							:class="{ active: isActive(['/horasdocente']) }">
							<router-link to="/horasdocente" class='sidebar-link'>Registro Horas Docente</router-link>
						</li>
						<li class="sidebar-item" v-if="hasPermission('ver parciales')"
							:class="{ active: isActive(['/parciales']) }">
							<router-link to="/parciales" class='sidebar-link'>Parciales</router-link>
						</li>
					</ul>
				</li>
				<li class="sidebar-header" v-if="hasPermission('ver seguimiento')">
					Seg
				</li>
				<li class="sidebar-item" v-if="hasPermission('ver seguimiento')"
					:class="{ active: isActive(['/seguimiento']) }">
					<a data-bs-target="#Seguimiento" data-bs-toggle="collapse" class="sidebar-link collapsed">
						<i class="bi bi-file-earmark-medical me-2"></i>
						<span class="align-middle">
							Seguimiento
							<!-- {{ getUserRole }} -->
						</span>
					</a>
					<ul id="Seguimiento" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
						<li class="sidebar-item" :class="{ active: isActive('/seguimiento') }">
							<router-link to="/seguimiento" class='sidebar-link'>Seguimiento Parciales</router-link>
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
		...mapGetters(['isCollapsed']),
		...mapGetters('auth', ['isAuthenticated', 'getUserRole', 'hasPermission']),
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