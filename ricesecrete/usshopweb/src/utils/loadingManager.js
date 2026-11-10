import { reactive } from "vue";

export const loadingState = reactive({
  isLoading: false, 
});

export const showLoading = () => {
  loadingState.isLoading = true;
};

export const hideLoading = () => {
  loadingState.isLoading = false;
};
