<script setup>
import { computed } from "vue"
import { loadingState } from "@/utils/loadingManager";
const isLoading = computed(() => loadingState.isLoading);
</script>
<template>
    <div v-if="isLoading"
        class="fixed w-screen h-screen top-0 left-0 z-[9999] flex items-center justify-center bg-[rgba(0,0,0,0.3)]">
        <section>
            <svg>
                <filter id="gooey">
                    <feGaussianBlur in="SourceGraphic" stdDeviation="10" />
                    <feColorMatrix values="
                        1 0 0 0 0
                        0 1 0 0 0
                        0 0 1 0 0
                        0 0 0 16 -3
                ">
                    </feColorMatrix>
                </filter>
            </svg>
            <div class="loader">
                <span class="rotate" style="--j:0;"></span>
                <span class="rotate" style="--j:1;"></span>
                <span class="rotate" style="--j:2;"></span>
                <span class="rotate" style="--j:3;"></span>
                <span class="rotate" style="--j:4;"></span>
                <span class="rotate" style="--j:5;"></span>
            </div>
        </section>
    </div>
</template>

<style lang="less" scoped>
.loader-content {
    position: relative;
    width: 240px;
    height: 197px;
    background: linear-gradient(313deg, #FFDEFD 0%, #E2DFFF 31%, #E0F8FF 58%, #FFEEF0 79%, #F8FAFF 100%);
    border-radius: 10px;
    border: 1px solid #6555B6;
}

section {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.loader {
    position: relative;
    width: 190px;
    height: 190px;
    filter: url(#gooey);
    transform: scale(0.25);
    @media (min-width: 640px) {
        transform: scale(0.35);
    }
}

.loader span {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: block;
}

.loader span::before {
    content: '';
    position: absolute;
    top: 0;
    left: calc(50% - 10px);
    width: 20px;
    height: 20px;
    background: linear-gradient(45deg, #F8FAFF, #FFEEF0,#E0F8FF,#E2DFFF);
    border-radius: 50%;
    transform: scale(calc(1 + var(--j) * 0.13));
    // box-shadow: 0 0 4px #00bcd4; 
}

.rotate {
    animation: animate 2s ease-in-out infinite;
    animation-delay: calc(-0.15s * var(--j));
}

@keyframes animate {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

svg {
    width: 0;
    height: 0;
}
</style>