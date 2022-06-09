<template>
  <div class="bannerContainer">
    <swiper class="bannerSwiper"
      cycle
      hide-delimiter-background
      :options="swiperOption"
    >
      <swiper-slide class="banner"
        v-for="(banner, idx) in bannerList"
        :key="`banner-${idx}`"
        :style="bannerStyle(banner)"       
      >
        <div class="bannerClickArea" @click="openWindow(banner.link)"></div>
      </swiper-slide>
      <div class="moveBtn prev" slot="button-prev"
        v-show="!windowTypeMobile && swiperOption.loop"
      ></div>
      <div class="moveBtn next" slot="button-next"
        v-show="!windowTypeMobile && swiperOption.loop"
      ></div>
    </swiper>
    <div class="bannerCloseBtn"
      @click="closeBanner"
    ></div>
  </div>
</template>

<script>
import constant from '@/constant/constant.js'

const FILE_ROOT = 'http://49.50.174.146/upload-file/'

export default {
  props: {
  },
  data() {
    return {
      bannerList: [],
      bannerOn: true,
      isNotOpenTodayBanner: false,
      swiperOption: { 
        slidesPerView: 1, 
        spaceBetween: 30, 
        loop: true,
        pagination: { 
          el: '.swiper-pagination', 
          clickable: true
        }, 
        navigation: { 
          nextEl: '.next', 
          prevEl: '.prev' 
        },
        autoplay: {
          delay: 10000,
        }
      },
    }
  },
  created() {
  },
  mounted() {

    this.loadBannerList()
  },
  computed: {
    nowDateString() {

      let nowDate = new Date(Date.now())

      return String(nowDate.getFullYear()) + String(nowDate.getMonth() + 1).padStart(2, '0') + String(nowDate.getDate())
    },
    windowTypeMobile() { 

      return this.$store.state.displayType === constant.DISPLAY_TYPE.MOBILE
    }
  },
  methods: {
    loadBannerList() {

      let fileFormat = originFilePath => {

        return FILE_ROOT + originFilePath.substring(originFilePath.lastIndexOf('/') + 1)
      }

// 로컬 테스트
      // let formatFilePathMobile = fileFormat('배경_mobile.png')
      // let formatFilePathPc = fileFormat('배경_pc.png')
      // for (var i = 0; i < 5; i++) {

      //   this.bannerList.push({
      //     fileList: [
      //       {
      //         filePath: formatFilePathMobile,
      //         type: "mobile"
      //       },
      //       {
      //         filePath: formatFilePathPc,
      //         type: "pc"
      //       },
      //     ],
      //     key: i,
      //     link: '',
      //   })
      // }
//////////////

      let bannerDto = (originBanner) => {

        return {
          fileList: [
            {
              filePath: fileFormat(originBanner.filePath),
              type: originBanner.fileType
            }
          ],
          key: originBanner.key,
          link: originBanner.link,
        }
      }

      this.$Connect('/banner', 'GET').then(bannerList => {

        bannerList.forEach(originBanner => {

          let resultBanner = this.bannerList.find(banner => banner.key === originBanner.key)

            if(resultBanner) {

              resultBanner.fileList.push(bannerDto(originBanner).fileList[0])
            } else {

              this.bannerList.push(bannerDto(originBanner))
            }
        })

        this.swiperOption.loop = this.bannerList.length > 1 ? true : false;
        this.isBannerEmit(this.bannerList.length !== 0)
      }).catch(error => {

        alert(error)
      })
    },
    isBannerEmit(flag) {

      this.$emit('isBanner', flag)
    },
    openWindow(link) {
      if (link !== '') {
        window.open(link, '_blank')
      }
    },
    bannerStyle(banner) {

      return this.windowTypeMobile ? 
        `background-image: url(${banner.fileList.find(fl => fl.type === 'mobile').filePath});` : 
        `background-image: url(${banner.fileList.find(fl => fl.type === 'pc').filePath});`
    },
    /**
     * 팝업 닫기 이벤트 발생 시
     */
    closeBanner() {

      this.isBannerEmit(false)
    },
  }
}
</script>