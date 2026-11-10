import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { AuthService } from "@/api/home";
export const CartStore = defineStore('cart', () => {
  const cartList = ref([]);
  const address = ref({})
  const getcartList = async() => {
    if(cartList.value.length > 0)return;
    const res = await AuthService.usercartlist()
    cartList.value = res.logistics_list
  }

  const newcartlist = async () =>{
    const res = await AuthService.usercartlist()
    cartList.value = res.logistics_list
  }
  const clearCartStore = () =>{
    cartList.value = []
    address.value = {}
  }
  const total = computed(()=>{
    return cartList.value.reduce((sum, item) => sum + item.number, 0)
  })
   return {
    cartList,
    total,
    getcartList,
    newcartlist,
    clearCartStore,
   }
})