<template>
	<v-container>
		<v-layout row wrap>
			<v-flex xs12>
				<v-card>
					<v-card-title>
						<v-layout row wrap>
							<v-flex xs12>
								<h1>公告</h1>
							</v-flex>
							<v-flex xs12>
								<span class="subheading">Announcement</span>
							</v-flex>
						</v-layout>
					</v-card-title>
					<v-card-text>
						<p>欢迎来到MlTree Forum官方论坛 <br>
							新版MlTreeForum-V1.0.2.20190220_Alpha已经上线，本次更新插件功能。 <br>
							可以尝鲜啦~</p>
					</v-card-text>
				</v-card>
			</v-flex>
		</v-layout>

		<v-layout row wrap>
			<v-subheader>置顶</v-subheader>
			<v-flex xs12>
				<v-card>
					<v-card-text>
						<v-list two-line>
							<template v-for="(item,index) in tops">

								<v-list-tile :key="index" avatar @click="">
									<v-list-tile-avatar>
										<img :src="item.user.avatar">
									</v-list-tile-avatar>

									<v-list-tile-content>
										<v-list-tile-title v-html="item.title"></v-list-tile-title>
										<v-list-tile-sub-title>
											<a href="">{{item.user.name}}</a>
											· {{item.time}} · {{item.reply}}回复 · {{item.views}}阅读
										</v-list-tile-sub-title>
									</v-list-tile-content>
								</v-list-tile>

								<v-divider inset v-if="index+1 < tops.length"></v-divider>
							</template>

						</v-list>
					</v-card-text>
				</v-card>
			</v-flex>

			<v-subheader>所有帖子</v-subheader>
			<v-flex xs12>
				<v-card>
					<v-card-text>
						<v-list two-line>
							<template v-for="(item,index) in commons">

								<v-list-tile :key="index" avatar @click="">
									<v-list-tile-avatar>
										<img :src="item.user.avatar">
									</v-list-tile-avatar>

									<v-list-tile-content>
										<v-list-tile-title v-html="item.title"></v-list-tile-title>
										<v-list-tile-sub-title>
											<a href="">{{item.user.name}}</a>
											· {{item.time}} · {{item.reply}}回复 · {{item.views}}阅读
										</v-list-tile-sub-title>
									</v-list-tile-content>
								</v-list-tile>

								<v-divider inset v-if="index+1 < tops.length"></v-divider>
							</template>

						</v-list>
					</v-card-text>
				</v-card>
			</v-flex>
		</v-layout>
		<v-btn fixed dark fab bottom right color="pink">
			<v-icon>add</v-icon>
		</v-btn>
	</v-container>
</template>

<script>
	export default {
		data() {
			return {
				tops: [{
					title: '测试标题一',
					user: {
						name: '十载北林',
						uid: '1',
						avatar: 'https://cdn.vuetifyjs.com/images/lists/1.jpg',
					},
					time: '2018-28-27 12:42:17',
					reply: '6',
					views: '1657'
				}, {
					title: '测试标题二',
					user: {
						name: '测试缪奥',
						uid: '1',
						avatar: 'https://cdn.vuetifyjs.com/images/lists/1.jpg',
					},
					time: '2018-28-27 12:42:17',
					reply: '6',
					views: '1657'
				}],
				commons: [{
					title: '测试标题一',
					user: {
						name: '十载北林',
						uid: '1',
						avatar: 'https://cdn.vuetifyjs.com/images/lists/1.jpg',
					},
					time: '2018-28-27 12:42:17',
					reply: '6',
					views: '1657'
				}, {
					title: '测试标题二',
					user: {
						name: '测试缪奥',
						uid: '1',
						avatar: 'https://cdn.vuetifyjs.com/images/lists/1.jpg',
					},
					time: '2018-28-27 12:42:17',
					reply: '6',
					views: '1657'
				}]
			}
		}
	}
</script>
