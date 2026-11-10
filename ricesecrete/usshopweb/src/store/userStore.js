import { defineStore } from "pinia";
import { ref } from "vue";
import { UserService } from '@/api/user'
export const useUserStore = defineStore('system', () => {
   const backUrl = ref()
   const token = ref({});
   const userInfo = ref({})
   const getUserInfo = async () => {
      const res = await UserService.userInfo();
      userInfo.value = res
      if(res?.currency_info?.code){
         localStorage.setItem('sys_currency',res?.currency_info?.code)
      }
      
   }
   const deleteToken = async() =>{
      token.value = {}
   }
   return {
      token,
      backUrl,
      userInfo,
      getUserInfo,
      deleteToken,
   }
},
   // 持久化存储
   {
      persist: true,
   }
)