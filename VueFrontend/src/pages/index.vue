<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { CalendarOutlined } from '@ant-design/icons-vue';

const baseURL = '/ty-json';
const hostURL = 'http://typecho.localhost:81/ty-json';
const apiURL = hostURL;

const loading = ref(false);
const articles = ref([]);
const siteTitle = ref(null);
const pagination = ref({
  current: 1,
  pageSize: 5,
  total: 0,
  onChange: (page) => {
    pagination.value.current = page;
    fetchArticles();
  },
});

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('zh-CN');
};

const fetchArticles = async () => {
  try {
    loading.value = true;
    const response = await axios.get(`${apiURL}/posts`, {
      params: {
        page: pagination.value.current,
        pageSize: pagination.value.pageSize,
      }
    });

    if (response.data.code === 200) {
      articles.value = response.data.data; 
      pagination.value.total = response.data.meta.pagination.total; 
    }
  } catch (error) {
    console.error('Failed to fetch articles:', error);
  } finally {
    loading.value = false;
  }
};
// const fetchSiteTitle = async () => {
//   try {
//     const response = await axios.get(`${apiURL}/options/title`);
//     if (response.data.code === 200) {
//       siteTitle.value = response.data.data.value;
//       document.title = siteTitle.value;
//     }
//   } catch (error) {
//     console.error('Failed to fetch site title:', error);
//     // 显示错误提示
//   }
// };

onMounted(() => {
  fetchArticles();
  // fetchSiteTitle();
});
</script>

<template>
  <a-card>
    <a-skeleton v-if="loading" v-for="i in 3" active />
    
    <template v-else-if="articles.length > 0">
      <a-list item-layout="vertical" size="large" :pagination="pagination" :data-source="articles">
        <template #renderItem="{ item }">
          <router-link :to="`/article/${item.cid}`">
            <a-list-item :key="item.cid">
              <template #actions>
                <span>
                  <CalendarOutlined />
                  {{ formatDate(item.created) }}
                </span>
              </template>
              <a-list-item-meta :description="item.excerpt">
                <template #title>
                  <router-link :to="`/article/${item.cid}`">{{ item.title }}</router-link>
                </template>
              </a-list-item-meta>
            </a-list-item>
          </router-link>
        </template>
      </a-list>
    </template>

    <template v-else>
      <a-empty description="没有数据" />
    </template>
  </a-card>
</template>

<style scoped>
.pagination {
  margin-top: 16px;
  text-align: center;
}
</style>