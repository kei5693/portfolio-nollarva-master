<template>
  <div>
    <div class="toggleBtn" :class="{'on': isFooterBannerToggleOpened}"
      @click="isFooterBannerToggleOpened = !isFooterBannerToggleOpened"
    >
      <img src="../../assets/img/banner/toggle_up.png" alt="">
    </div>
    <div class="footerBannerContent" :class="{'on': isFooterBannerToggleOpened}">
      <h3 class="title">창업문의</h3>
      <div class="formContainer">
        <form>
          <input
            type="text"
            v-model="sendDataLocation"
            placeholder="동단위 지역명 (필수)"
          />
          <input
            type="text"
            v-model="sendDataName"
            placeholder="신청자 성명 (필수)"
          />
          <input
            type="text"
            v-model="sendDataPhone"
            placeholder="신청자 전화번호 (필수)"
          />
          <input
            type="text"
            v-model="sendDataQuestion"
            placeholder="기타 문의 사항"
          />
        </form>
        <div class="confirmContainer">
          <span class="agree">
            <input type="checkbox" v-model="sendDataMarketing" id="inp_agree" />
            <label for="inp_agree">개인 정보 수집 및 마케팅 활용 동의</label>
          </span>
          <button type="button" @click="layerPopupToggle()">
            <span>내용보기</span>
          </button>
        </div>
      </div>
      <button type="button" class="btnConfirm" @click="isValidation()">
        상담요청
      </button>

      <transition name="popupDimmed" mode="out-in">
        <div id="layerPopup" v-show="isLayerOpended">
          <div class="inner">
            <p class="intro">
              창업 상담 내역 확인 및 답변 안내를 위해 아래와 같이 개인정보를
              수집하고 있습니다.
            </p>
            <ol>
              <li>
                1. 정보수집, 이용목적<br />
                <p>
                  투바앤리테일(이하 '당사')은 상기 기재된 귀하의 개인정보를
                  수집하여 매장 창업, 창업정보 제공 등의 목적으로만 이용합니다.
                </p>
              </li>
              <li>
                2. 수집 개인정보 항목<br />
                <ul>
                  <li>- 성명</li>
                  <li>- 연락처</li>
                  <li>- 지역</li>
                  <li>- 창업관련 정보 등</li>
                </ul>
              </li>
              <li>
                3. 개인정보 보유 및 이용기간<br />
                <ul>
                  <li>
                    - 위 목적범위를 초과하여 개인정보를 이용하거나 타인 또는
                    타업·기관에 제공하지 않습니다.
                  </li>
                  <li>
                    - 위 목적이 달성된 후 개인정보를 즉시 파기합니다. 단, 위
                    목적이 달성되지 않았다 하더라도 최초 상담일로부터 5년이 되는
                    때 즉시 파기합니다.
                  </li>
                </ul>
              </li>
              <li>
                4. 개인정보 제공 등의 거부 권리 및 동의거부에 따른 불이익 또는
                제한사항<br />
                <p>
                  귀하는 당사의 위 개인정보 수집·이용을 거부할 수 있습니다. 단,
                  거부하는 경우, 당사로부터 창업정보 등을 제공받지 못하는 불이익을
                  받을 수 있습니다.<br />
                  그 밖의 사항은 개인정보취급방침을 준수합니다.
                </p>
              </li>
            </ol>
            <button type="button" @click="layerPopupToggle()">닫기</button>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isFooterBannerToggleOpened: true,
      isLayerOpended: false,
      sendDataLocation: "",
      sendDataName: "",
      sendDataPhone: "",
      sendDataQuestion: "",
      sendDataMarketing: false,
    };
  },
  computed: {
    sendDataValidation() {
      return (
        this.sendDataLocation.length > 0 &&
        this.sendDataName.length > 0 &&
        this.sendDataPhone.length > 0 &&
        this.sendDataMarketing
      );
    },
  },
  methods: {
    layerPopupToggle() {
      document.body.classList.toggle("on");
      this.isLayerOpended = !this.isLayerOpended;
    },
    async isValidation() {
      if (!this.sendDataValidation) {
        alert(
          "필수항목 및 개인 정보 수집 및 마케팅 활용동의를\n 체크하여 주시기 바랍니다."
        );
        return false;
      }

      this.axios({
        method: "post",
        url: "/PHPMailer/sender.php",
        data: {
          location: this.sendDataLocation,
          name: this.sendDataName,
          phone: this.sendDataPhone,
          question: this.sendDataQuestion,
        },
      })
        .then((response) => {
          var resData = response.data;
          if (!resData.success) {
            alert(resData.errMsg);

            return false;
          }

          alert("창업상담신청 전송이 완료되었습니다.");
          this.sendDataLocation = "";
          this.sendDataName = "";
          this.sendDataPhone = "";
          this.sendDataQuestion = "";
          this.sendDataMarketing = false;
        })
        .catch(function(error) {
          console.log("error", error);
          alert("메일 발송중 오류가 발생 하였습니다.");
        });
    },
  },
};
</script>
