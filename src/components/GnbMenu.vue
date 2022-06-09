<template>
  <div id="gnbMenu">
    <div class="inner">
      <ul class="menus">
        <li class="menu"
          v-for="(gnb, index) in gnbMenu"
          :key="`gnb ${index}`"
          :data-key="index"
          @click="$emit('moveToPage', index)"
        >{{gnb.title}}
        </li>
      </ul>
      <button type="button" @click="$emit('gnbMenuToggle', false)">닫기</button>
      <SnsLinkButtons></SnsLinkButtons>
    </div>
  </div>
</template>
<script>
import SnsLinkButtons from '@/components/SnsLinkButtons.vue'

export default {
  props:['gnbMenu'],
  components: {
    SnsLinkButtons
  }
}
</script>