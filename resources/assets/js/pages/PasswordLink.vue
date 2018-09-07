<template>
  <div class="password-reset">
    <el-card class="password-reset-card">
      <div slot="header">
        <span>Password reset</span>
      </div>
      <password-link-form/>
    </el-card>
  </div>
</template>

<script>
import PasswordLinkForm from '../components/PasswordLinkForm'

export default {
  components: { PasswordLinkForm }
}
</script>