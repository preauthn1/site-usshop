import { defineStore } from "pinia";
import { ref } from "vue";
import { AuthService } from "@/api/home";
export const menuStore = defineStore('menu', () => {
  const menuList = ref([]);
  const getmenuList = async() => {
    if(!menuList.value.length > 0){
        const res = await AuthService.classification();
        menuList.value = res
    }
    return menuList.value
  }
   return {
    menuList,
    getmenuList,
   }
})