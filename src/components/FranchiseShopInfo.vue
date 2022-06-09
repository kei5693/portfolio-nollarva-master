<template>
  <div id="franchiseShopsInformation" >
    <h3>매장안내</h3>
    <p class="fsiGuide">매장을 선택하면<br/>매장 위치를 지도로 볼 수 있습니다.</p>
    <ul class="fsiTable">
      <li class="header cellContainer">
        <div class="index">번호</div>
        <div class="title">매장명</div>
        <div class="address">매장주소</div>
      </li>
      <li class="body">
        <div class="franchiseShopContainer"
          v-for="(franchiseShop, fIndex) in franchiseShopList"
          :key="`franchiseShop-${fIndex}`"
        >
          <div class="franchiseShop cellContainer" 
            @click="showFranchiseShopDetailSwitch(fIndex)"
          >
            <div class="index">{{ franchiseShopList.length - fIndex + (10 * (franchiseShopListPagination.pageSize - franchiseShopListPagination.nowPage)) }}</div>
            <div class="title">{{ franchiseShop.title }}</div>
            <div class="address">
              <span>{{ franchiseShop.address }}</span>
              <span> (T.{{ franchiseShop.phoneNumber}} )</span>
            </div>
          </div>
          <div class="franchiseShopDetail" 
            v-if="franchiseShop.detailOn">
            <div class="franchiseShopLocationContainer"
              ref="franchiseShopLocationContainer"
              id="franchiseShopLocationContainer"
            >                 
              <div class="fslImg">
                <div class="fslImgContainer">
                  <swiper class="swiper"
                    cycle
                    hide-delimiter-background
                    :options="swiperOption"
                  >
                    <swiper-slide class="swiperImg"
                      v-for="(img, imgIndex) in franchiseShop.imgFile.franchiseShopList"
                      :key="`franchiseShop-${fIndex}-Img-${imgIndex}`"
                      :style="`background-image: url(${img.filePath})`"                            
                    ></swiper-slide>
                    <div class="swiperButton swiperButtonPrev"
                      v-show="franchiseShop.imgFile.franchiseShopList.length > 1"
                      slot="button-prev"
                    ></div>
                    <div class="swiperButton swiperButtonNext"
                      v-show="franchiseShop.imgFile.franchiseShopList.length > 1"
                      slot="button-next"
                    ></div>
                  </swiper>
                </div>
              </div>
              <div class="fslImg" v-if="franchiseShop.imgFile.districtList.length > 0">
                <div class="fslImgContainer">
                  <swiper class="swiper"
                    cycle
                    hide-delimiter-background
                    :options="swiperOption"
                  >
                    <swiper-slide class="swiperImg"
                      v-for="(img, imgIndex) in franchiseShop.imgFile.districtList"
                      :key="`franchiseShop-${fIndex}-districtImg-${imgIndex}`"
                      :style="`background-image: url(${img.filePath})`"                            
                    ></swiper-slide>
                    <div class="swiperButton swiperButtonPrev"
                      v-show="franchiseShop.imgFile.districtList.length > 1"
                      slot="button-prev"
                    ></div>
                    <div class="swiperButton swiperButtonNext"
                      v-show="franchiseShop.imgFile.districtList.length > 1"
                      slot="button-next"
                    ></div>
                  </swiper>
                </div>
              </div>
            </div>

            <div class="fslContent"
              v-if="franchiseShop.etcDesc !== '' || franchiseShop.url !== ''"
            >
              <p>{{franchiseShop.etcDesc}}</p>
              <p @click="openWindow">{{franchiseShop.url}}</p>
            </div>

            <div class="naverMapArea" :data-idx="fIndex" :data-latlng="`${franchiseShop.mapOptions.lat}/${franchiseShop.mapOptions.lng}`">
              <article>
                <naver-maps
                  class="naverMaps"
                  :key="`naverMaps-${fIndex}`"
                  :domId="`naverMaps-${fIndex}`"
                  :mapOptions="franchiseShop.mapOptions"
                >
                  <naver-marker :lat="franchiseShop.mapOptions.lat" :lng="franchiseShop.mapOptions.lng"></naver-marker>
                </naver-maps>
              </article>
            </div>
          </div>
        </div>
      </li>
    </ul>
    <div class="fsiPagination">
      <Pagination 
        :pagePostMaxSize="franchiseShopListPagination.pagePostMaxSize"
        :totalPostLength="franchiseShopListPagination.totalPostLength"
        :pageSetMaxSize="windowTypeMobile? 5 : 10"
        @movePage="movePageFranchiseShopList"
      />
    </div>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination.vue'
import { Swiper, SwiperSlide } from 'vue-awesome-swiper'
import constant from '@/constant/constant.js'

export default {
  components: {
    Pagination,
    Swiper,
    SwiperSlide,
  },
  data() {
    return {
      franchiseShopListPagination: {
        nowPage: 1,
        pagePostMaxSize: 10,
        totalPostLength: 0,
        pageSize: 0,
      },
      franchiseShopList: [],
      swiperOption: { 
        slidesPerView: 1, 
        spaceBetween: 30, 
        loop: false,
        pagination: { 
          el: '.swiper-pagination', 
          clickable: true
        }, 
        navigation: { 
          nextEl: '.swiperButtonNext', 
          prevEl: '.swiperButtonPrev' 
        } 
      },
    }
  },
  mounted() {
    this.loadFranchiseShopList()
  },
  computed: {
    windowTypeMobile() {

      return this.$store.state.displayType === constant.DISPLAY_TYPE.MOBILE
    }
  },
  methods: {
    openWindow(url) {
      
      window.open(url, "_blank")
    },
    showFranchiseShopDetailSwitch(franchiseShopIndex) {

      this.franchiseShopList[franchiseShopIndex].detailOn = !this.franchiseShopList[franchiseShopIndex].detailOn
    },
    loadFranchiseShopList() {

      let params = {
        currentPage: this.franchiseShopListPagination.nowPage,
      }

      this.$Connect('/franchise', 'GET', {}, params).then(data => {

        this.franchiseShopListPagination.totalPostLength = data.totalCount
        this.franchiseShopListPagination.pagePostMaxSize = data.limit
        this.franchiseShopListPagination.pageSize = data.pageSize

        this.franchiseShopList = data.data.map(franchiseShop => {

          let franchiseFileList = franchiseShop.franchiseFileList.map(img => {
            // filePathName = '1644330710146'
            // filePathName = '1643008571028'
            return {
              filePath: `http://49.50.174.146/upload-file/${img.filePath.substring(img.filePath.lastIndexOf('/') + 1)}`,
              fileType: img.type
            }
          })

          return {
            key: franchiseShop.key,
            title: franchiseShop.name,
            address: franchiseShop.address,
            phoneNumber: franchiseShop.phoneNumber,
            url: franchiseShop.url,
            etcDesc: franchiseShop.etcDesc,
            mapOptions: {
              lat: parseFloat(franchiseShop.lat),
              lng: parseFloat(franchiseShop.lng),
              zoomControl: false,
              draggable: true
            },
            imgFile: {
              franchiseShopList: franchiseFileList.filter(data => data.fileType === 'franchiseShop'),
              districtList: franchiseFileList.filter(data => data.fileType === 'district')
            },
            detailOn: false,
          }
        })
      }).catch(error => {

        alert(error)
      })
    },
    movePageFranchiseShopList(page) {

      this.franchiseShopListPagination.nowPage = page;
      this.loadFranchiseShopList()
    },
  },
}
</script>