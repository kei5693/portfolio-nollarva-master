<template>
    <div id="contact">
        <div class="inner">
          <div>
            <div class="info">
              <h2>놀라바 무인 스마트 스토어</h2>
              <ul>
                <li>
                  <span>가맹사업문의.</span>
                  <span><strong>1544-0510</strong></span>
                </li>
                <li>
                  <span>주소</span>
                  <span>서울특별시 종로구 경희궁 1길 1  투바앤빌딩<br>(우. 03176)</span>
                </li>
              </ul>
            </div>

            <naver-maps class="naverMaps" :domId="`mainMap`" :width="1270" :height="478" :mapOptions="mapOptions">
              <naver-marker :lat="37.5706558427026" :lng="126.971684721382"></naver-marker>
            </naver-maps>
          </div>
        </div>
        <footer id="footer">
          <img src="~@/assets/img/common/img_footer_logo.png" alt="놀라바" />
          <address>
            <span>주소 : 서울특별시 종로구 경희궁 1길 1 투바앤빌딩 (우03176)</span>
            <span>문의 전화 : 1544-0510</span>
          </address>
          <p>Copyright 2021 TubanRetail all rights reserved.</p>
        </footer>
      </div>
</template>