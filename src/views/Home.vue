<template>
  <div id="wrap">
    <header>
      <div class="gnb">
        <h1>놀라바 로고</h1>
        <div class="faqBtn"
          @click="changeNowPageIndex(4)"
        >창업상담신청</div>
        <ul>
          <li
            v-for="(gnb, index) in gnbMenu"
            :key="`gnb ${index}`"
            @click="changeNowPageIndex(index, 'header')"
            ref="gnb"
          >
            <span>{{gnb.title}}</span>
          </li>
        </ul>
        <button type="button" @click="gnbMenuToggle(true)">GNB 메뉴</button>
        <div 
          class="franchisePlaceBtn" alt=""
          @click="openWindow(`http://놀라바.kr`)"
        ></div>
      </div>
      <Banner
        id="topBanner"
        @isBanner="isBannerCheck"
        v-show="isBanner"
      />
    </header>

    <section id="contents" ref="contents"
      :class="isBanner ? 'isBanner' : ''"
    >
      <Introduce />
      <Brand />
      <Franchise />
      <Faq />
      <Counseling />
      <FranchiseShopInfo />
      <Contact />
    </section>

    <GnbMenu
      :gnbMenu="gnbMenu"
      @gnbMenuToggle="gnbMenuToggle"
      @moveToPage="changeNowPageIndex"
      :class="{'on' : isOpended}"
    />

    <FooterBannerCounseling id="footerBanner"/>

    <transition name="popupDimmed" mode="out-in">
      <div
        id="dimmed"
        v-show="isOpended"
      ></div>
    </transition>
  </div>
</template>

<script>
import GnbMenu from "@/components/GnbMenu.vue"
import Banner from '@/components/Banner.vue'
import FooterBannerCounseling from '@/components/counseling/FooterBannerCounseling.vue'
import 'swiper/css/swiper.css'
import constant from '@/constant/constant.js'

import Introduce from '@/components/Introduce.vue'
import Brand from '@/components/Brand.vue'
import Franchise from '@/components/Franchise.vue'
import Faq from '@/components/Faq.vue'
import Counseling from '@/components/counseling/Counseling.vue'
import FranchiseShopInfo from '@/components/FranchiseShopInfo.vue'
import Contact from '@/components/Contact.vue'

export default {
  components: {
    GnbMenu,
    Banner,
    FooterBannerCounseling,

    Introduce,
    Brand,
    Franchise,
    Faq,
    Counseling,
    FranchiseShopInfo,
    Contact
  },
  data() {
    return {
      gnbMenu: [
        {title:'놀라바 소개', type:"scroll"},
        {title:'브랜드 경쟁력', type:"scroll"},
        {title:'가맹안내', type:"scroll"},
        {title:'FAQ', type:"scroll"},
        {title:'창업상담신청', type:"scroll"},
        {title:'매장안내', type:"scroll"},
        {title:'Contact', type:"scroll"},
        {title:'가맹점주님 공간', type:"link"},
      ],
      isOpended: false,
      isMobile: false,
      validation: false,
      currentPage: 0,
      positionArr: [],
      mapOptions: {
        lat: 37.5706558427026,
        lng: 126.971684721382,
        zoomControl: false,
        draggable: true
      },
      isMainPopupOpened: true,
      isBanner: false,
    };
  },
  created() {
    window.addEventListener('scroll', this.scrollEvent);
    // this.bodyCssFuncWhenMainPopup(true)
  },
  mounted() {
    this.checkMobile()

    this.$nextTick(() => {
      this.updateWindowType()
    });
    window.onresize = () => {
      this.updateWindowType()
    }
    window.onload = () => {
      this.moveToPage(this.nowPageIndex)
      this.siblingsClass(this.nowPageIndex);
    }
  },
  destroyed() {
    window.removeEventListener('scroll', this.scrollEvent);
  },
  computed: {
    windowTypeMobile() {
      return this.$store.state.displayType === constant.DISPLAY_TYPE.MOBILE
    },
    nowPageIndex() {
      const qPageIndex = this.$route.query.page_index
      if (qPageIndex && this.gnbMenu.length > qPageIndex) {
        
        return qPageIndex
      } else {
        return 0;
      }
    }
  },
  watch: {
    nowPageIndex() {
      this.moveToPage(this.nowPageIndex)
    }
  },
  methods: {
    /**
     * 윈도우 타입 업데이트
     */
    updateWindowType() {
      let windowWidth = window.innerWidth
      
      if (windowWidth < 768) {
        this.$store.commit('setDisplayType', constant.DISPLAY_TYPE.MOBILE)
      } else if (windowWidth >= 768 && windowWidth < 1240) {
        this.$store.commit('setDisplayType', constant.DISPLAY_TYPE.LAPTOP)
      } else if (windowWidth >= 1240) {
        this.$store.commit('setDisplayType', constant.DISPLAY_TYPE.PC)
      }
    },
    changeNowPageIndex(value) {
      this.$router.push({query: {page_index: value.toString()}})
    },
    /**
     * 페이지 이동
     */
    moveToPage(value, header){
      if(this.gnbMenu[value].type == "link"){

        window.open("http://놀라바.kr", "_black")
      } else {
        // 모바일 클릭 방지
        if( this.isMobile && header != undefined ) return

        // 클릭한 대상으로 스크롤 이동
        var _scrollPos
        if (value == 0) {
          _scrollPos = 0
          this.currentPage = 0
        } else {
          _scrollPos = this.$refs.contents.children[value].offsetTop;
        }
         
        window.scrollTo({ top: _scrollPos});
      }
      // 팝업 닫기
      if(this.isOpended){this.isOpended = false}
    },
    /**
     * 햄버거 메뉴 토글
     */
    gnbMenuToggle(value){
      this.isOpended = value;
    },
    /**
     * 스크롤 발생 시 각 컨텐츠의 시작, 끝 위지 저장
     * 현재 스크롤 위치
     */
    scrollEvent(){
      this.positionArr = [];
      var _target =  this.$refs.contents.children,
          _windowY = window.pageYOffset+50;

      _target.forEach(element => {
        this.positionArr.push([element.offsetTop, element.offsetTop + element.clientHeight])
      });

      this.positionArr.filter((e,index) =>{
        if(e[0] <= _windowY && _windowY <= e[1] ){
          if(this.currentPage != index){
            this.currentPage = index;
          } else {
            return
          }
        }
      });
      this.siblingsClass(this.currentPage);
    },
    checkMobile(){
      if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        this.isMobile = true
      }
    },
    /**
     * 클릭한 대상만 on, 나머지는 remove
     */
    siblingsClass(_currentPage){
      var _gnb = this.$refs.gnb;
      Array.prototype.slice.call(_gnb).filter(function(e, index) {
        return index != _currentPage ? e.classList.remove('on') : e.classList.add('on');
      });
    },
    openWindow(url) {
      
      window.open(url, "_blank")
    },

    isBannerCheck(flag) {

      this.isBanner = flag
    }
    /**
     * body 스크롤 컨트롤
     */
    // bodyCssFuncWhenMainPopup(flag) {

    //   const wrapperHtml = document.querySelector('body')

    //   if (flag) {

    //     wrapperHtml.classList.add('scrollDisable')
    //   } else {

    //     wrapperHtml.classList.remove('scrollDisable')    
    //   }
    // },
  },
};
</script>
<style>
.popupDimmed-enter,
.popupDimmed-leave-to {
  opacity: 0;
}
.popupDimmed-enter-active,
.popupDimmed-leave-active {
  transition: opacity 0.2s;
}
</style>