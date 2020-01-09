<template>
<!-- Modal -->
  <b-modal ref="paymentProcessingLoader" id="session-timeout-dialog" :body-class="'text-center'" centered hide-header hide-footer hide-header-close no-close-on-esc no-close-on-backdrop>
       <h3>Por favor, aguarde...</h3>
      <p>Estamos processando o seu pedido!</p>
      <div class="progress">
            <div class="progress-bar progress-bar-striped countdown-bar active" role="progressbar" style="min-width: 15px; width: 100%;">
            <span class="countdown-holder"></span>
            </div>
        </div>
  </b-modal>
<!-- Modal -->
</template>
<script>
export default {

  name: 'Countdown',

   data: () => ({
      onWarn: false,
      countdownMessage: true,
      countdownBar: true,
      countdownSmart: true,
      timer: '',
      countdown: {},
      warnAfter: 900000, // 15 minutes
      redirAfter: 40000 // 20 minutes
    }),


  mounted() {
    this.$bus.$on('countdown::close', this.hideModal)
  },

  methods: {

      blockPage () {
        this.startTimer()
      },

      startTimer () {
        // Clear timer
        clearTimeout(this.timer)
        if (this.countdownMessage || this.countdownBar) {
          this.startCountdownTimer('session', true)
        }

        this.$refs.paymentProcessingLoader.show();
        this.startDialogTimer()
      },


      startDialogTimer() {

        clearTimeout(this.timer);

        if(document.getElementById('session-timeout-dialog').classList.contains('in') && (this.countdownMessage || this.countdownBar)) {
          this.startCountdownTimer('dialog', true)
        }        

        this.$bus.$on('payment:success', this.hideModal);
      },

      hideModal() {
        this.$refs.paymentProcessingLoader.hide();
        this.countdown = {};
      },

      startCountdownTimer(type, reset) {
        let self = this;
        let countdown = this.countdown;
        let timer = this.timer;
        // Clear countdown timer
        clearTimeout(countdown.timer);

        if (type === 'dialog' && reset) {
          // If triggered by startDialogTimer start warning countdown
          this.countdown.timeLeft = Math.floor(this.redirAfter / 1000);
        } else if (type === 'session' && reset) {
          // If triggered by startSessionTimer start full countdown
          // (this is needed if user doesn't close the warning dialog)
          this.countdown.timeLeft = Math.floor(this.redirAfter / 1000);
        }
        // If opt.countdownBar is true, calculate remaining time percentage
        if (this.countdownBar && type === 'dialog') {
          this.countdown.percentLeft = Math.floor(this.countdown.timeLeft / (this.redirAfter / 1000) * 100);
        } else if (this.countdownBar && type === 'session') {
          this.countdown.percentLeft = Math.floor(this.countdown.timeLeft / (this.redirAfter / 1000) * 100);
        }
        // Set countdown message time value
        var countdownEl = document.getElementsByClassName('countdown-holder');
        var secondsLeft = this.countdown.timeLeft >= 0 ? this.countdown.timeLeft : 0;
        if (this.countdownSmart) {
          let minLeft = Math.floor(secondsLeft / 60);
          let secRemain = secondsLeft % 60;
          let countTxt = minLeft > 0 ? minLeft + 'm' : '';
          if (countTxt.length > 0) {
            countTxt += ' ';
          }
          countTxt += secRemain + 's';
          countdownEl.innerHTML = countTxt;
        } else {
          countdownEl.innerHTML = secondsLeft + "s";
        }

        // Set countdown message time value
        if (this.countdownBar) {
          if (typeof document.getElementsByClassName('countdown-bar')[0] == 'undefined') {
            return;
          }
          document.getElementsByClassName('countdown-bar')[0].style.width = this.countdown.percentLeft + '%';
        }

        // Countdown by one second
        this.countdown.timeLeft = this.countdown.timeLeft - 1;
        this.countdown.timer = setTimeout(() => {
          // Call self after one second
          this.startCountdownTimer(type);
        }, 1000);
      }
  },

}

</script>

<style>
  .progress {
    height: 2.5rem;
  }

  .countdown-holder {
    left: 0;
    width: 100%;
    font-size: 17px;
    font-weight: 700;
    text-align: center;
    position: fixed;
    color: #eff0fb;
  }
</style>
