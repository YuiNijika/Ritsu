<script setup>
document.title = `404 - 页面未找到`;
</script>

<template>
    <a-result status="404" title="404" sub-title="Sorry, the page you visited does not exist.">
        <template #extra>
            <a-button type="primary"><router-link to="/">返回首页</router-link></a-button>
        </template>
    </a-result>
</template>