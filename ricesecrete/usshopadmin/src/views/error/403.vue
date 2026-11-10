<template>
    <div class="error">
        <img src="../../assets/imgs/error-403.png">
        <div class="tips">
            <p>抱歉，您无权访问此页面</p>
            <p class="u-mt-20">
                <router-link to="/">
                    <el-button type="primary" size="small">返回首页</el-button>
                </router-link>
            </p>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    @import "error.scss";
</style>