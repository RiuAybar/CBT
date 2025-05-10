<template>
	<nav id="sidebar" class="sidebar js-sidebar" :class="{ collapsed: isCollapsed }">
		<div class="sidebar-content js-simplebar">
			<router-link to="/" class='sidebar-brand'>
				<span class="sidebar-brand-text align-middle">
					C B T
					<sup><small class="badge bg-primary text-uppercase">Instituto de Estudios</small></sup>
				</span>
				<svg class="sidebar-brand-icon align-middle" width="32px" height="32px" viewBox="0 0 24 24" fill="none"
					stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter" color="#FFFFFF"
					style="margin-left: -3px">
					<path d="M12 4L20 8.00004L12 12L4 8.00004L12 4Z"></path>
					<path d="M20 12L12 16L4 12"></path>
					<path d="M20 16L12 20L4 16"></path>
				</svg>
			</router-link>

			<ul class="sidebar-nav">
				<li class="sidebar-header">
					Pages
				</li>
				<li class="sidebar-item" :class="{ active: isActive(['/permisos', '/roles']) }">
					<a data-bs-target="#dashboards" data-bs-toggle="collapse" class="sidebar-link collapsed">
						<i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Roles y
							Permisos</span>
					</a>
					<ul id="dashboards" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
						<li class="sidebar-item" :class="{ active: isActive('/permisos') }">
							<router-link to="/permisos" class='sidebar-link'>Permisos</router-link>
						</li>
						<li class="sidebar-item" :class="{ active: isActive('/roles') }">
							<router-link to="/roles" class='sidebar-link'>Roles</router-link>
						</li>
						<li class="sidebar-item">
							<a class='sidebar-link' href='/dashboard-ecommerce'>E-Commerce <span
									class="sidebar-badge badge bg-primary">Pro</span></a>
						</li>
						<li class="sidebar-item">
							<a class='sidebar-link' href='/dashboard-crypto'>Crypto <span
									class="sidebar-badge badge bg-primary">Pro</span></a>
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