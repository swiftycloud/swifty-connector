<template>
  <div class="signup-success">
    <el-card>
      <div slot="header">
        <span>
          <i class="el-icon-success"></i>
          <span>Last step!</span>
        </span>
      </div>

      <p class="message">Please check your mailbox...</p>
      <p class="signin-link"><router-link :to="{ name: 'signin' }">Sign In</router-link></p>
    </el-card>
  </div>
</template>

<style lang="scss">
.signup-success {
  .el-card {
    text-align: center;
    width: 496px;
    padding: 0 !important;
    margin: 50px auto;

    .el-icon-success {
      color: #67C23A;
    }

    .message {
      font-size: 18px;
    }
  }
}
</style>