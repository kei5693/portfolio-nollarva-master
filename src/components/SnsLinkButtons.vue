<template>
  <ul class="snsLinks">
    <li @click="openWindow(`https://m.facebook.com/%EB%86%80%EB%9D%BC%EB%B0%94-100773915823716/`)">
      <img src="../assets/img/common/facebook_icon.png" alt="페이스북" class="facebookIcon">
    </li>
    <li @click="alertStandby">
      <img src="../assets/img/common/naver_blog_icon.png" alt="네이버블로그" class="naverBlogIcon" >
    </li>
  </ul>
</template>

<script>
export default {
  methods: {
    openWindow(url) {
      
      window.open(url, "_blank")
    },
    alertStandby() {
      
      alert('준비중입니다.')
    },
  },
}
</script>