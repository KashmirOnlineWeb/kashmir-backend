import { defineStore } from 'pinia';

export const useSliderStore = defineStore('slider', {
  state: () => ({
    sliderData: [],
    isUploading: false
  }),
  actions: {
    setSliderData(data) {
      this.sliderData = data;
    },
    updateSliderData(data) {
      this.sliderData = data;
    },
    setUploadingStatus(status) {
      this.isUploading = status;
    }
  },
  getters: {
    getSliderData: (state) => state.sliderData,
    getUploadingStatus: (state) => state.isUploading
  }
});