<template>
    <div class="changepsd">
        <Navbar :title="$t('更改密码')" />
        <div class="mt-[44px]">
            <van-form @failed="onFailed" class="p-4 text-[12px]">
                <!-- 通过 pattern 进行正则校验 -->
                <div>
                    <div class=" title">{{$t('旧密码')}}：</div>
                    <van-field v-model="fromInfo.old_password" name="pattern" :placeholder="$t('请输入旧密码')" :type="iseye1 ? 'password' : 'text'"
                        class="border-b border-borderColor" :rules="[{ pattern, message: $t('请输入正确内容') }]">
                        <template #button>
                            <van-icon @click="iseye1 = !iseye1" :name="iseye1 ? 'closed-eye' : 'eye-o'" />
                        </template>
                    </van-field>
                </div>
                <div class="pt-4">
                    <div class=" title">{{$t('新密码')}}：</div>
                    <van-field v-model="fromInfo.password" name="pattern" :placeholder="$t('请输入新密码')" :type="iseye2 ? 'password' : 'text'"
                        class="border-b border-borderColor" :rules="[{ pattern, message: $t('请输入正确内容') }]">
                        <template #button>
                            <van-icon @click="iseye2 = !iseye2" :name="iseye2 ? 'closed-eye' : 'eye-o'" />
                        </template>
                    </van-field>
                </div>
                <div class="pt-4">
                    <div class=" title">{{$t('确认密码')}}：</div>
                    <van-field v-model="fromInfo.confirm_password" name="pattern" :type="iseye3 ? 'password' : 'text'" :placeholder="$t('请输入确认密码')" class="border-b border-borderColor"
                        :rules="[{ pattern, message: $t('请输入正确内容') }]">
                        <template #button>
                            <van-icon @click="iseye3 = !iseye3" :name="iseye3 ? 'closed-eye' : 'eye-o'" />
                        </template>
                    </van-field>
                </div>
            </van-form>
            <div>
                <div @click="changePass" class="my-10 flex w-[80%] mx-auto justify-center items-center py-4 bg-black text-white font-bold">
                    {{$t('提交')}}</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue'
import { reactive, ref } from 'vue';
import { showLoadingToast } from 'vant';
import { UserService } from '@/api/user'
import { useUserStore } from '@/store/userStore';
import { useRouter } from 'vue-router';
const userStore = useUserStore();
const router = useRouter()
const iseye1 = ref(false)
const iseye2 = ref(false)
const iseye3 = ref(false)
const pattern = /\d{6}/;
// const fromInfo = reactive({
//     oldpsd:'',
//     newpsd:'',
//     conformpsd:'',
// })
const onFailed = (errorInfo) => {
    console.log('failed', errorInfo);
};
const fromInfo = ref({
    old_password:'',
    password: '',
    confirm_password: '',
});
const changePass =  async ()=> {
    const res = await UserService.changePassword(fromInfo.value);
    await userStore.deleteToken();
    router.push("/login");
}

</script>

<style lang="less" scoped>
.title {
    color: rgba(0, 0, 0, .298039)
}
</style>