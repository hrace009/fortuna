import Vue from 'vue'
import Child from './Child'
import Button from './Button'
import Portlet from './Portlet'
import Countdown from './Countdown'
import FileUpload from './FileUpload'
import FileUploader from './FileUploader'
import FileUploadProgress from './FileUploadProgress'
import AttachmentUpload from './AttachmentUpload'
import { HasError, AlertError, AlertSuccess } from 'vform'

[
  Child,
  Button,
  Countdown,
  Portlet,
  FileUpload,
  FileUploader,
  FileUploadProgress,
  HasError,
  AlertError,
  AlertSuccess,
  AttachmentUpload
].forEach(Component => {
  Vue.component(Component.name, Component)
})
