<div class="contact-us contact-us--small">
    <div class="container">
        <form class="form" name="contact_us_form" id="contact_us_form" action="{{url('/contact_us')}}" method="post">
            {{csrf_field()}}
            <div class="form">
                <div class="form__inner">
                    <div class="contact-us__title">Got questions? Contact us today</div>
                    <div class="form__row form__row--three">
                        <div class="form__field">
                            <div class="field field--contact">
                                <input type="email" id="contact_email" name="contact_email" placeholder="example@gmail.com" required>
                                <label for="contact-email">Email</label>
                            </div>
                        </div>
                        <div class="form__field">
                            <div class="field field--contact">
                                <input type="tel" id="contact_phone" name="contact_phone" placeholder="+11-111-111-1111" required>
                                <label for="contact-phone">Phone</label>
                            </div>
                        </div>
                        <div class="form__field">
                            <button type="button" id="request_contact" class="form__submit form__submit--large">send contact request</button>
                        </div>
                    </div>
                </div>

                <div class="form__reaction form__reaction--success">
                    <div class="form__reaction-inner">
                        <div class="form__reaction-title">Congratulations!</div>
                        <div class="form__reaction-img">
                            <img src="{{ asset('frontend/assets/icons/form-success.svg') }}" alt="">
                        </div>
                        <div class="form__reaction__desc">
                            Your message was <br> successfully sent
                        </div>
                    </div>
                </div>

                <div class="form__reaction form__reaction--fail">
                    <div class="form__reaction-inner">
                        <div class="form__reaction-title">Failure!</div>
                        <div class="form__reaction-img">
                            <img src="{{ asset('frontend/assets/icons/form-fail.svg') }}" alt="">
                        </div>
                        <div class="form__reaction__desc">
                            Sorry, something went wrong. <br>
                            Please try again.
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>