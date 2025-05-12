<script setup>
import { ref, onMounted, onUnmounted, nextTick, computed } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';
import {
    CalendarOutlined,
    FolderOutlined,
    TagsOutlined,
    ArrowLeftOutlined,
    MenuOutlined,
    CloseOutlined
} from '@ant-design/icons-vue';
import * as Prism from 'prismjs';
import 'prismjs/themes/prism-tomorrow.css';
import VueEasyLightbox from 'vue-easy-lightbox';
import 'vue-easy-lightbox/external-css/vue-easy-lightbox.css';

const router = useRouter();
const route = useRoute();

const baseURL = '/API';
const hostURL = 'http://typecho.localhost:81/API';
const apiURL = baseURL;

const loading = ref(false);
const article = ref(null);
const error = ref(null);
const visibleRef = ref(false);
const indexRef = ref(0);
const imgsRef = ref([]);
const headings = ref([]);
const activeHeadingId = ref('');
const tocVisible = ref(false);
const isMobile = ref(false);

const checkIsMobile = () => {
    isMobile.value = window.innerWidth <= 768;
};

onMounted(() => {
    checkIsMobile();
    window.addEventListener('resize', checkIsMobile);
    fetchArticleContent();
});

onUnmounted(() => {
    window.removeEventListener('resize', checkIsMobile);
});

const fetchArticleContent = async () => {
    try {
        loading.value = true;
        error.value = null;
        const response = await axios.get(apiURL + `/content/${route.params.cid}`);

        if (response.data.code === 200) {
            article.value = response.data.data;
            document.title = `${article.value.title}`;
            await nextTick();
            highlightCode();
            initLightbox();
            generateTOC();
        } else {
            error.value = response.data.message || '获取文章内容失败，请稍后重试';
        }
    } catch (err) {
        error.value = '请到主题后台检查 TTDF REST API 设置是否启用';
        console.error('API请求错误:', err);
    } finally {
        loading.value = false;
    }
};

const generateTOC = () => {
    nextTick(() => {
        const contentEl = document.querySelector('.article-content');
        if (!contentEl) return;

        const headingElements = Array.from(contentEl.querySelectorAll('h1, h2, h3, h4, h5'));

        headings.value = headingElements.map((heading, index) => {
            const id = heading.id || `heading-${index}`;
            heading.id = id;

            return {
                id,
                text: heading.textContent,
                level: parseInt(heading.tagName.substring(1)),
                element: heading
            };
        });

        setupScrollSpy();
    });
};

const setupScrollSpy = () => {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    activeHeadingId.value = entry.target.id;
                }
            });
        },
        {
            rootMargin: '0px 0px -50% 0px',
            threshold: 0.5
        }
    );

    headings.value.forEach(heading => {
        observer.observe(heading.element);
    });
};

const scrollToHeading = (id) => {
    const element = document.getElementById(id);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
        activeHeadingId.value = id;
    }
    tocVisible.value = false;
};

const initLightbox = () => {
    const contentEl = document.querySelector('.article-content');
    if (!contentEl) return;

    const imgElements = contentEl.querySelectorAll('img');
    imgsRef.value = Array.from(imgElements).map(img => ({
        src: img.src,
        alt: img.alt || ''
    }));

    imgElements.forEach((img, index) => {
        img.style.cursor = 'pointer';
        img.addEventListener('click', () => {
            indexRef.value = index;
            visibleRef.value = true;
        });
    });
};

const highlightCode = () => {
    if (window.Prism) {
        window.Prism.highlightAll();
    }
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString('zh-CN', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const goHome = () => {
    router.push('/');
};

const shouldShowTOC = computed(() => {
    return headings.value.length > 0;
});
</script>

<template>
    <a-card class="article-container" size="default">
        <template v-if="error">
            <a-alert type="error" :message="error" banner show-icon class="error-alert" />
            <a-button type="primary" @click="fetchArticleContent" class="retry-button">
                重试
            </a-button>
        </template>

        <template v-else-if="article">
            <a-page-header class="article-header" :title="article.title" @back="() => null">
                <template #backIcon>
                    <ArrowLeftOutlined @click="goHome" />
                </template>
                <template v-if="!isMobile" #tags>
                    <a-tooltip>
                        <template #title>更新于 {{ formatDate(article.modified) }}</template>
                        <span class="meta-item">
                            <CalendarOutlined />
                            {{ formatDate(article.created) }}
                        </span>
                    </a-tooltip>
                </template>
                <template #extra>
                    <a-button v-if="shouldShowTOC" type="text" @click="tocVisible = !tocVisible">
                        <template #icon>
                            <MenuOutlined />
                        </template>
                        目录
                    </a-button>
                </template>
            </a-page-header>

            <a-divider />

            <div class="article-layout" :class="{ 'has-toc': shouldShowTOC }">
                <div class="article-container">
                    <div>
                        <div class="taxonomy-item" v-if="article.categories.length">
                            <FolderOutlined class="taxonomy-icon" />
                            <a-tag v-for="cat in article.categories" :key="cat.mid" color="blue" class="taxonomy-tag">
                                {{ cat.name }}
                            </a-tag>
                        </div>

                        <div class="taxonomy-item" v-if="article.tags.length">
                            <TagsOutlined class="taxonomy-icon" />
                            <a-tag v-for="tag in article.tags" :key="tag.mid" color="green" class="taxonomy-tag">
                                {{ tag.name }}
                            </a-tag>
                        </div>
                    </div>

                    <a-divider />

                    <div class="article-content" v-html="article.content" ref="contentRef"></div>

                    <a-float-button v-if="shouldShowTOC" type="text" @click="tocVisible = !tocVisible">
                        <template #tooltip>
                            <div>打开目录</div>
                        </template>
                        <template #icon>
                            <MenuOutlined />
                        </template>
                    </a-float-button>
                </div>

                <a-drawer v-if="shouldShowTOC" v-model:visible="tocVisible" placement="left" :closable="false" :width="300" :bodyStyle="{ padding: '16px' }" class="toc-drawer" title="目录">
                    <template #extra>
                        <a-button type="text" @click="tocVisible = false">
                            <CloseOutlined />关闭
                        </a-button>
                    </template>
                    <div class="toc-content">
                        <a-anchor :targetOffset="80">
                            <a-anchor-link v-for="heading in headings" :key="heading.id" :href="`#${heading.id}`" :title="heading.text" :class="{
                                'active': activeHeadingId === heading.id,
                                [`toc-level-${heading.level}`]: true
                            }" @click.prevent="scrollToHeading(heading.id)" />
                        </a-anchor>
                    </div>
                </a-drawer>
            </div>
        </template>

        <template v-if="loading">
            <div class="loading-container">
                <a-skeleton active />
            </div>
        </template>
        <template v-if="!loading && !error && !article">
            <a-empty description="文章不存在或已被删除" class="empty-container">
                <a-button type="primary" @click="$router.push('/')">
                    返回首页
                </a-button>
            </a-empty>
        </template>

        <vue-easy-lightbox :visible="visibleRef" :imgs="imgsRef" :index="indexRef" @hide="visibleRef = false" />
    </a-card>
</template>

<style scoped>
.toc-drawer {
    z-index: 1000;
}

.toc-drawer .toc-content {
    max-height: calc(100vh - 100px);
    overflow-y: auto;
}

.toc-drawer h3 {
    margin-bottom: 16px;
    font-size: 18px;
    font-weight: bold;
}

.toc-drawer .toc-content .ant-anchor-link {
    padding: 8px 0;
}

.toc-drawer .toc-content .ant-anchor-link.active {
    color: #1890ff;
    font-weight: bold;
}

.toc-drawer .toc-content .toc-level-1 {
    padding-left: 0;
}

.toc-drawer .toc-content .toc-level-2 {
    padding-left: 16px;
}

.toc-drawer .toc-content .toc-level-3 {
    padding-left: 32px;
}

.toc-drawer .toc-content .toc-level-4 {
    padding-left: 48px;
}

.toc-drawer .toc-content .toc-level-5 {
    padding-left: 64px;
}

.toc-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.toc-close-button {
    font-size: 20px;
}
</style>