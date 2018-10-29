

<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/custom.js') }}"></script>


@yield('scripts')

<script type="text/javascript">
    var vue = new Vue({
        el: '#app',
        mixins: [Main],
        data() {
            return {
                //
            }
        },
        mounted() {
            @if(session()->has('flash-message-success'))
                this.successToastMessage('{{ session()->get('flash-message-success') }}');
            @endif
            @if(session()->has('flash-message-warning'))
                this.warningToastMessage('{{ session()->get('flash-message-warning') }}');
            @endif
            @if(session()->has('flash-message-danger'))
                this.dangerToastMessage('{{ session()->get('flash-message-danger') }}');
            @endif
        },
        methods: {
            successToastMessage(message) {
                var that = this;
                that.$toast.open({
                    message: message,
                    duration: 4000,
                    position: 'is-bottom-right',
                    type: 'is-success'
                }); 
            },
            warningToastMessage(message) {
                var that = this;
                that.$toast.open({
                    message: message,
                    duration: 4000,
                    position: 'is-bottom-right',
                    type: 'is-warning'
                }); 
            },
            dangerToastMessage(message) {
                var that = this;
                that.$toast.open({
                    message: message,
                    duration: 4000,
                    position: 'is-bottom-right',
                    type: 'is-danger'
                }); 
            },
            
        }
  
    })
</script>