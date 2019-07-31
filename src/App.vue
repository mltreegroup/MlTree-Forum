<template>
	<v-app :dark="isDark">
		<v-toolbar app>
			<v-toolbar-side-icon @click="drawer = true"></v-toolbar-side-icon>
			<v-toolbar-title class="headline text-uppercase">
				<span>MlTreeForum</span>
			</v-toolbar-title>
			<v-toolbar-title class="headline text-uppercase">
				<span>首页</span>
			</v-toolbar-title>
			<v-spacer></v-spacer>
			<v-toolbar-items>
				<v-btn flat @click="chackDark">
					<v-icon>brightness_medium</v-icon>
				</v-btn>
			</v-toolbar-items>
		</v-toolbar>

		<v-navigation-drawer v-model="drawer" absolute temporary>
			<v-card color="blue-grey darken-2" class="white--text">
				<v-img src="https://cdn.vuetifyjs.com/images/cards/docks.jpg" height="200px">
					<v-container>
						<v-layout>
							<v-flex xs12 align-end flexbox>
								<v-avatar color="grey lighten-4">
									<img src="https://vuetifyjs.com/apple-touch-icon-180x180.png" alt="avatar">
								</v-avatar>
								<!-- <span class="headline">Top 10 Australian beaches</span> -->
							</v-flex>
						</v-layout>
						<v-layout mt-3 fill-height>
							<span class="headline">尚未登录</span>
							</v-flex>
						</v-layout>
					</v-container>
				</v-img>

				<v-card-actions>
					<v-spacer></v-spacer>
					<v-menu transition="slide-y-transition" bottom>
						<template v-slot:activator="{ on }">
							<v-btn class="purple" color="primary" dark v-on="on">
								<v-icon dark>expand_more</v-icon>
							</v-btn>
						</template>
						<v-list>
							<v-list-tile @click="">
								<v-list-tile-action>
									<v-icon>input</v-icon>
								</v-list-tile-action>
								<v-list-tile-content>
									<v-list-tile-title>登录</v-list-tile-title>
								</v-list-tile-content>
							</v-list-tile>
							<v-list-tile @click="">
								<v-list-tile-action>
									<v-icon>create</v-icon>
								</v-list-tile-action>
								<v-list-tile-content>
									<v-list-tile-title>注册</v-list-tile-title>
								</v-list-tile-content>
							</v-list-tile>
						</v-list>
					</v-menu>
				</v-card-actions>
			</v-card>

			<v-divider></v-divider>
			<v-subheader>板块列表</v-subheader>
			<v-list class="pt-0" dense>
				<v-list-tile v-for="item in items" :key="item.title" @click="">
					<v-list-tile-action>
						<v-icon>{{ item.icon }}</v-icon>
					</v-list-tile-action>

					<v-list-tile-content>
						<v-list-tile-title>{{ item.title }}</v-list-tile-title>
					</v-list-tile-content>
				</v-list-tile>
			</v-list>
			
			<v-divider></v-divider>
			<v-subheader>功能</v-subheader>
			<v-list>
				<v-list-tile to="/msgbox">
					<v-list-tile-action>
						<v-icon>chat</v-icon>
					</v-list-tile-action>
			
					<v-list-tile-content>
						<v-list-tile-title>消息盒子</v-list-tile-title>
					</v-list-tile-content>
				</v-list-tile>
			</v-list>

			<v-divider></v-divider>
			<v-subheader>友情链接</v-subheader>
			<v-list>
				<v-list-tile href="https://forum.kingsr.cc" target="_blank">
					<v-list-tile-action>
						<v-icon>launch</v-icon>
					</v-list-tile-action>

					<v-list-tile-content>
						<v-list-tile-title>十载北林Blog</v-list-tile-title>
					</v-list-tile-content>
				</v-list-tile>
			</v-list>

			<v-divider></v-divider>
			<v-subheader>支持</v-subheader>
			<v-list>
				<v-list-tile href="https://forum.kingsr.cc" target="_blank">
					<v-list-tile-action>
						<v-icon>launch</v-icon>
					</v-list-tile-action>

					<v-list-tile-content>
						<v-list-tile-title>MlTree Forum</v-list-tile-title>
					</v-list-tile-content>
				</v-list-tile>
			</v-list>


		</v-navigation-drawer>

		<v-content>
			<router-view />
		</v-content>
	</v-app>
</template>

<script>
	export default {
		name: 'App',
		data() {
			return {
				drawer: null,
				items: [{
						title: 'Home',
						icon: 'dashboard'
					},
					{
						title: 'About',
						icon: 'question_answer'
					}
				],
			}
		},
		computed: {
			isDark() {
				return this.$store.state.isDark;
			}
		},
		methods: {
			chackDark() {
				this.$store.commit('changeDark');
			}
		},
	}
</script>
