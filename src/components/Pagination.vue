<template>
  <div class="pagination">
    <button class="movePageBtn moveFirstPage"
      @click="nowPage = 1"
      :class="nowPage != 1 ? '' : 'disabled'"
    ></button>
    <button class="movePageBtn movePrevPage"
      :class="nowPage != 1 ? '' : 'disabled'"
      @click="nowPage == 1 ? nowPage = nowPage : nowPage = nowPage - 1"
    > 
    </button>
    <div class="pageBtns">
      <button class="pageBtn"
        @click="nowPage = page"
        v-for="page in nowPageSet"
        :key="page"
        :class="page == nowPage ? 'selected' : ''"
      >
        {{ page }}
      </button>
    </div>
    <button class="movePageBtn moveNextPage"
      :class="nowPage != totalPageSize ? '' : 'disabled'"
      @click="nowPage == totalPageSize ? nowPage = nowPage : nowPage = nowPage + 1"
    ></button>
    <button class="movePageBtn moveLastPage"
      :class="nowPage != totalPageSize ? '' : 'disabled'"
      @click="nowPage = totalPageSize"
    ></button>
  </div>
</template>

<script>
export default {
  props: {
    pagePostMaxSize: Number, // 한 페이지에 들어가는 max 게시물 수
    totalPostLength: Number, // 총 게시물 수
    pageSetMaxSize: Number, // 한 세트에 나와야하는 max 페이지 수
  },
  data() {
    return {
      nowPage: 1,
    }
  },
  watch: {
    nowPage() {
      
      this.$emit('movePage', this.nowPage)
    }
  },
  computed: {
    /**
     * 전체 페이지 수
     */
    totalPageSize() {

      return Math.ceil(this.totalPostLength / this.pagePostMaxSize)
    },
    /**
     * 현재 페이지 셋 인덱스
     */
    nowPageSetIdx() {

      return this.nowPage % this.pageSetMaxSize == 0 ? this.nowPage / this.pageSetMaxSize - 1 : Math.floor(this.nowPage / this.pageSetMaxSize)
    },
    /**
     * 현재 페이지 셋의 페이지 배열
     */
    nowPageSet() {
      
      let minPage = this.nowPageSetIdx * this.pageSetMaxSize + 1
      let maxPage = (this.nowPageSetIdx + 1) * this.pageSetMaxSize > this.totalPageSize ? this.totalPageSize : (this.nowPageSetIdx + 1) * this.pageSetMaxSize

      let result = []

      for (let page = minPage; page <= maxPage; page++) {
        result.push(page)
      }

      return result
    }
  },
}
</script>

<style>
</style>