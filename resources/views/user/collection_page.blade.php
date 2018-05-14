<?php if($collections != false) { ?>
    <?php foreach($collections as $collection) { ?>
<div class="collection_page_<?php echo $page; ?>">
    <div class="collection" data-id="<?php echo $collection['id']; ?>"; id="collection_<?php echo $collection['id']; ?>" style="display: none;">
        <div class="collection__left">
            <div class="collection__img lazyload" data-sizes="auto" data-bgset="<?php echo $collection['filename']; ?> 1x, <?php echo  $collection['filename']; ?>  2x"  style='background-image: url({{ asset("/frontend/images/thumb-loquare.png") }});'></div>
        </div>
        <div class="collection__center">
            <a href="{{ url('/property/detail/'.$collection['id'])  }}" class="collection__title"><?php echo $collection['direccion']; ?></a>
            <div class="collection__top">
                <div class="collection__top-item">
                    <div class="collection__price">€<?php echo ($collection['property_deal'] == "SALE")?$collection['price']:$collection['price']."/mo"; ?></div>
                </div>
                <div class="collection__top-item"><?php echo ($collection['usability']== 1)?"Used":"New"; ?> <?php echo $collection['property_type']; ?></div>
                <div class="collection__top-item"><?php echo $collection['rooms']; ?> bed, <?php echo $collection['bathrooms']; ?> baths, <?php echo $collection['sizem2']; ?>m<sup>2</sup></div>
            </div>
            <div class="collection__comment">
                <div class="comment">
                    <div class="comment__label">Your comment</div>
                    <div class="comment__body">
                        <div class="comment__text">
                            <span><?php echo $collection['comment']; ?></span>
                            <a href="#" class="comment__readmore js-toggle-trunc" data-more="show more" data-less="show less"></a>
                        </div>
                        <textarea name="" id="" class="comment__area js-auto-size"><?php echo $collection['comment']; ?></textarea>
                    </div>
                    <button type="button" class="comment__edit-btn js-toggle-edit-comment">
                        <svg width="19px" height="19px" viewBox="0 0 19 19" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g transform="translate(-415.000000, -325.000000)" fill-rule="nonzero" fill="#5D6872">
                                    <g transform="translate(135.000000, 135.000000)">
                                        <path d="M280,193.838459 C280.001064,193.347618 280.398704,192.949978 280.889545,192.948914 L290.425077,192.948914 L289.616751,194.728003 L280.889545,194.728003 C280.398704,194.72694 280.001064,194.3293 280,193.838459 Z M280,204.766322 C280.001064,205.257162 280.398704,205.654803 280.889545,205.655866 L285.66988,205.655866 C285.680834,205.059083 285.739682,204.464142 285.845855,203.876777 L280.889545,203.876777 C280.398704,203.877841 280.001064,204.275481 280,204.766322 L280,204.766322 Z M280.889545,198.365468 L287.967226,198.365468 L288.775551,196.586378 L280.889545,196.586378 C280.398263,196.586378 280,196.984641 280,197.475923 C280,197.967205 280.398263,198.365468 280.889545,198.365468 L280.889545,198.365468 Z M280,201.111453 C280.001064,201.602294 280.398704,201.999934 280.889545,202.000998 L286.354443,202.000998 C286.451133,201.735424 286.55878,201.473073 286.677386,201.213944 L287.12796,200.221909 L280.889545,200.221909 C280.398704,200.222972 280.001064,200.620613 280,201.111453 Z M297.514361,194.90978 L296.53006,194.468875 L296.916819,193.623808 C297.296656,192.863653 297.230623,191.956679 296.744686,191.259558 C296.25875,190.562437 295.430639,190.186678 294.586013,190.28005 C293.741386,190.373422 293.015348,190.92099 292.693416,191.707419 L288.085187,201.852096 C287.242895,203.707643 287.004135,205.780729 287.402559,207.779171 C287.440167,207.970189 287.567637,208.131321 287.744872,208.211883 C287.922107,208.292444 288.127324,208.282533 288.295971,208.185267 L288.295971,208.185267 C290.062819,207.169268 291.466293,205.624432 292.308591,203.768485 L295.8571,195.959829 L296.8356,196.402668 C296.908995,196.436056 296.941784,196.522343 296.909084,196.596047 L295.004298,200.792378 C294.876875,201.060726 294.90418,201.376951 295.075717,201.619484 C295.247255,201.862018 295.536321,201.993106 295.83179,201.962355 C296.127259,201.931603 296.383136,201.743798 296.501053,201.471139 L298.405839,197.274809 C298.809519,196.375447 298.411261,195.318898 297.514361,194.90978 Z"></path>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </button>
                    <div class="comment__actions">
                        <button type="button" class="comment__save">save</button>
                        <button type="button" class="comment__save-cancel js-comment-cancel">cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="collection__right">
            <button class="collection__unsave js-popup-open" data-id="<?php echo $collection['id']; ?>"; data-mfp-src="#unsave" type="button">
                <svg width="23px" height="22px" viewBox="0 0 23 22" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g transform="translate(-1264.000000, -148.000000)" fill="#EC644B">
                            <g transform="translate(535.000000, 130.000000)">
                                <path d="M747.67564,20.2126895 L748.741883,19.1464466 L750.156097,20.5606602 L745.921038,24.7957191 L751.461622,25.6008131 L746.230811,30.6995935 L747.465638,37.8991869 L741,34.5 L734.534362,37.8991869 L734.889773,35.8269841 L731.064214,39.6525433 L729.65,38.2383297 L730.705078,37.1832522 L730.714214,37.1923882 L747.684776,20.2218254 L747.67564,20.2126895 Z M743.008503,22.0696716 L735.064998,30.0131766 L730.538378,25.6008131 L737.767181,24.5504065 L741,18 L743.008503,22.0696716 Z"></path>
                            </g>
                        </g>
                    </g>
                </svg>
            </button>
            <div class="collection__share">
                <!--<button class="collection__share-btn js-toggle-share" type="button"></button> -->
                <a href="javascript:void(0)" style="font-size: 16px;" data-id="<?php echo $collection['id']; ?>" data-mfp-src="#invite-property" class="my-properties__action js-popup-open invite_property_popup"><span class="user_plus_icon" title="Invite"><i class="fa fa-user-plus" aria-hidden="true"></i></span></a>
                <div class="collection__share-list js-share-list">
                    <div class="share">
                        <button type="button" class="share__btn share__btn--fb share-fb  js-share" data-type="fb" data-share="<?php echo $collection['id']; ?>">
                            <svg width="26px" height="26px" viewBox="0 0 26 26" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-1263.000000, -1007.000000)" fill-rule="nonzero" fill="#E2E2E2">
                                        <g transform="translate(1252.000000, 949.000000)">
                                            <g transform="translate(11.000000, 58.000000)">
                                                <path d="M13,0 C5.82074952,0 0,5.82074952 0,13 C0,20.1792505 5.82074952,26 13,26 C20.1792505,26 26,20.1792505 26,13 C26,5.81914587 20.1792505,0 13,0 Z M16.3604911,12.9983429 L14.2301025,12.9999465 L14.2284988,20.7999145 L11.3051078,20.7999145 L11.3051078,13 L9.3551292,13 L9.3551292,10.3122379 L11.3051078,10.3106342 L11.3018471,8.72789026 C11.3018471,6.53415763 11.8965855,5.20003207 14.4787209,5.20003207 L16.6302242,5.20003207 L16.6302242,7.88939785 L15.2847127,7.88939785 C14.2788533,7.88939785 14.2301025,8.26475764 14.2301025,8.96512278 L14.2268417,10.3106342 L16.6448173,10.3106342 L16.3604911,12.9983429 Z"></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </button>
                        <button type="button" class="share__btn share__btn--tw share-tw js-share"  data-type="tw" data-share="<?php echo $collection['id']; ?>">
                            <svg width="26px" height="26px" viewBox="0 0 26 26" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-1263.000000, -1047.000000)" fill-rule="nonzero" fill="#E2E2E2">
                                        <g transform="translate(1252.000000, 949.000000)">
                                            <g transform="translate(11.000000, 98.000000)">
                                                <path d="M13,0 C5.82074952,0 0,5.82074952 0,13 C0,20.1792505 5.82074952,26 13,26 C20.1792505,26 26,20.1792505 26,13 C26,5.81914587 20.1792505,0 13,0 Z M19.4674995,10.0814734 L19.4772282,10.4958552 C19.4772282,14.7273393 16.2581251,19.602366 10.3707174,19.602366 C8.56373049,19.602366 6.88182783,19.0726287 5.46484728,18.1642173 C5.71512278,18.1934571 5.97020921,18.2097074 6.22860985,18.2097074 C7.72849882,18.2097074 9.10811444,17.697824 10.2033504,16.8398206 C8.80262011,16.8138415 7.62121499,15.8875763 7.21335466,14.6168481 C7.40996151,14.6542131 7.60822546,14.6721204 7.81621819,14.6721204 C8.10872301,14.6721204 8.39144558,14.6347555 8.65957499,14.5616293 C7.19544729,14.2658637 6.09208622,12.974021 6.09208622,11.4237775 L6.09208622,11.3831519 C6.52432195,11.6236451 7.01669435,11.7666368 7.5415673,11.7845442 C6.68356387,11.2109204 6.11806526,10.23104 6.11806526,9.12115742 C6.11806526,8.53454415 6.27570355,7.98529581 6.55030099,7.51243442 C8.12818056,9.44942351 10.4876766,10.7234124 13.1478026,10.8566753 C13.0925303,10.62265 13.0649476,10.3772924 13.0649476,10.1270703 C13.0649476,8.36070906 14.4981784,6.92742479 16.2661968,6.92742479 C17.1859406,6.92742479 18.0179649,7.31577411 18.6029211,7.93814865 C19.3309224,7.79349989 20.0166944,7.52702758 20.6358082,7.16139657 C20.3969185,7.90890886 19.889953,8.5361478 19.2285564,8.93262225 C19.8737027,8.8562353 20.4928165,8.68234675 21.0664402,8.42886396 C20.6374653,9.06909242 20.0930813,9.63298738 19.4674995,10.0814734 Z" ></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </button>
                        <a href="#" class="share__btn share__btn--email share-mail"  data-type="mail" data-share="<?php echo $collection['id']; ?>" target="_self">
                            <svg width="26px" height="26px" viewBox="0 0 26 26" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-1263.000000, -1087.000000)" fill-rule="nonzero" fill="#E2E2E2">
                                        <g transform="translate(1252.000000, 949.000000)">
                                            <g transform="translate(11.000000, 138.000000)">
                                                <path d="M13,26 C11.804,26 10.6513333,25.844 9.542,25.532 C8.43266667,25.2198725 7.397,24.7823333 6.435,24.219 C5.473,23.6556667 4.59766667,22.9796667 3.809,22.191 C3.02033333,21.4024183 2.34433333,20.527 1.781,19.565 C1.21766667,18.602915 0.78,17.5673333 0.468,16.4579575 C0.156,15.3486667 0,14.196 0,13 C0,11.804 0.156,10.6513333 0.468,9.542 C0.78,8.43266667 1.21766667,7.397 1.781,6.435 C2.34433333,5.473 3.02033333,4.59766667 3.809,3.809 C4.59766667,3.02033333 5.473,2.34433333 6.435,1.781 C7.397,1.21766667 8.43266667,0.78 9.542,0.468 C10.6513333,0.156 11.804,0 13,0 C14.7853333,0 16.471,0.342333333 18.057,1.027 C19.643,1.71166667 21.021,2.639 22.191,3.809 C23.361,4.979 24.2883333,6.357 24.973,7.943 C25.6576667,9.529 26,11.2146667 26,13 C26,14.196 25.844,15.3486667 25.532,16.458 C25.2198725,17.5672059 24.7823333,18.6028725 24.219,19.565 C23.6556667,20.527 22.9796667,21.4023333 22.191,22.191 C21.4024183,22.9796667 20.527,23.6557941 19.565,24.219 C18.602915,24.7823333 17.5673333,25.22 16.457915,25.532 C15.3486667,25.844 14.196,26 13,26 Z M19.266,7.644 L6.942,7.644 C6.82066667,7.644 6.71666667,7.683 6.63,7.761 C6.54333333,7.839 6.5,7.94733333 6.5,8.086 L6.5,9.464 C6.5,9.53333333 6.526,9.568 6.578,9.568 L13.052,13.26 L13.1041275,13.286 C13.1386667,13.286 13.1647941,13.2773333 13.1821275,13.26 L19.4481275,9.568 C19.4826667,9.55066667 19.5087941,9.542 19.5261275,9.542 C19.5433333,9.542 19.5694608,9.53333333 19.6041275,9.516 C19.6734608,9.516 19.7081275,9.48133333 19.7081275,9.412 L19.7081275,8.086 C19.7081275,7.94733333 19.6647516,7.839 19.578,7.761 C19.4912484,7.683 19.3873333,7.644 19.266,7.644 Z M10.426,13.182 C10.4433333,13.1646667 10.452,13.1386242 10.452,13.104 C10.452,13.0518725 10.4346667,13.026 10.4,13.026 L6.682,10.8939575 C6.63,10.8766242 6.58666667,10.8766242 6.552,10.8939575 C6.51733333,10.8939575 6.5,10.9199575 6.5,10.9719575 L6.5,16.5619575 C6.5,16.614085 6.526,16.6486242 6.578,16.6659575 L6.63,16.6659575 C6.66466667,16.6659575 6.682,16.6572908 6.682,16.6399575 L10.426,13.182 Z M14.95,13.598 C14.9326667,13.5458725 14.8892059,13.5373333 14.8198725,13.572 L13.3898725,14.404 C13.2165392,14.508 13.0346667,14.508 12.8438725,14.404 L11.5958725,13.7021275 C11.5438725,13.6674608 11.5005392,13.6674608 11.4658725,13.7021275 L6.70787255,18.1221275 C6.69053922,18.1393333 6.68187255,18.1741275 6.68187255,18.226 C6.68187255,18.2433333 6.69920588,18.2693758 6.73387255,18.304 C6.83787255,18.3386667 6.90720588,18.3561275 6.94187255,18.3561275 L19.135915,18.3561275 C19.1705817,18.3561275 19.2053758,18.3387941 19.239915,18.304 C19.239915,18.2346667 19.2312484,18.1914608 19.213915,18.174 L14.95,13.598 Z M19.63,10.79 L19.5261275,10.79 L15.9901275,12.896 C15.9555882,12.896 15.9381275,12.922 15.9381275,12.974 C15.9207941,12.9913333 15.9294608,13.0173333 15.964,13.0518725 L19.5,16.874 C19.5346667,16.9086667 19.5606667,16.9261275 19.578,16.9261275 L19.63,16.9261275 C19.6821275,16.874 19.7081275,16.8394608 19.7081275,16.8221275 L19.7081275,10.8940425 C19.708,10.8593333 19.682,10.8246667 19.63,10.79 Z" ></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="">
                <a href="javascript:void(0)" style="padding-top: 15px;font-size: 20px;" data-id="<?php echo $collection['id']; ?>" data-mfp-src="#collection_setting" class="my-properties__action js-popup-open collection_setting_popup"><span class="user_plus_icon" title="Settings"><i class="fa fa-cog"></i></span></a>
                <!--<a href="javascript:void(0)" style="font-size: 20px;" data-id="<?php echo $collection['id']; ?>" data-mfp-src="#collection_chat" class="my-properties__action js-popup-open collection_chat_popup"><span class="user_plus_icon" title="Settings"><i class="fa fa-comment"></i></span></a>-->
            </div>
        </div>
    </div>
    <style>

    </style>
    <div class="chat_window" id="chat_window_<?php echo $collection['id']; ?>" style="display: none;">
        <form id="remove_users" method="post" action="{{url('property/chat/remove/user')}}">
            {{ csrf_field() }}
            <input type="hidden" name="collection_id" value="<?php echo $collection['id']; ?>">
            <div style="width: 100%;float: left;">
                @if(count($collection['invited_users']) > 0)
                    @forelse($collection['invited_users'] as $user)
                        <div class="ck-button">
                           <label>
                              <input type="checkbox" name="invited_users[]" onclick="checkboxLength();" value="{{$user->id}}"><span>{{substr($user->user->name, 0, 1)}}{{substr($user->user->lastname, 0, 1)}}</span>
                           </label>
                        </div>
                    @empty
                    @endforelse
                @endif
                <div class="check_icon_view">
                    @if(count($collection['invited_users']) > 0)
                        <span style="display: none;" id="remove_user_icon" onclick="$( '#remove_users' ).submit();"><i class="fa fa-trash"></i></span>
                    @endif
                    <span><i class="fa fa-times" onclick="$('#chat_window_<?php echo $collection['id']; ?>').hide();"></i></span>
                </div>
            </div>
        </form>
        <div class="popup__title">
            Chat
        </div>
        <div id="chat_messages_<?php echo $collection['id']; ?>" style="    overflow: auto;
    height: 250px;">
        </div>
        <div class="popup-form__row" style="padding: 20px 0 0 0;">
            <div class="search-add" style="justify-content: center;">
                <input class="search-add__field message_input" type="text" name="message_<?php echo $collection['id']; ?>" id="message_<?php echo $collection['id']; ?>" style="width: 570px;" placeholder="Type here..." onkeypress="return enter_message(event,<?php echo $collection['id']; ?>);">
                <button class="search-add__btn" type="button" style="text-transform: none;" onclick="return send_message(<?php echo $collection['id']; ?>);">Send</button>
            </div>
        </div>
    </div>
</div>
    <?php } ?>
    <input type="hidden" name="chat_id" id="chat_id" value="">
    <input type="hidden" name="property_id_active" id="property_id_active" value="">
    <!--<div id="collection_chat" class="mfp-hide popup popup--460" style="padding: 10px;">
        <div class="popup__inner">
            <div class="popup__title">
                Discussion
            </div>
            <div class="popup-form__row" id="chat_window" style="height: 350px;overflow: auto">

            </div>
            <div class="popup-form__row" style="padding: 20px 0 0 0;">
                <div class="search-add" style="justify-content: center;">
                    <input type="hidden" name="chat_id" id="chat_id" value="">
                    <input class="search-add__field message_input" type="text" name="message" id="message" style="width: 400px;" placeholder="Type here..." onkeypress="return enter_message(event);">
                    <button class="search-add__btn" type="button" style="text-transform: none;" onclick="return send_message();">Send</button>
                </div>
            </div>
        </div>
    </div>-->

    <div id="collection_setting" class="mfp-hide popup popup--460">
        <div class="popup__inner">
            <div class="popup__title">
                Collection
            </div>
            <form method="POST" action="{{ url('/property/switch/collection') }}">
            {{ csrf_field() }}
            <div class="popup-form__row" style="padding: 20px 0 0 0;">
                <div class="search-add" style="justify-content: center;">
                    <select class="custom-select" style="width:100%;" name="collectionid" id="collectionid" onchange="return checkCollection(this.value);">
                        <option value="0">Please select collection</option>
                        @forelse($user_collections as $user_collection)
                        <option value="{{$user_collection['id']}}">{{$user_collection['collection']}}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>
            <div class="popup-form__btns" style="justify-content: center;padding-top: 20px;">
                <input type="hidden" name="propertyid" id="propertyid"/>
                <input type="hidden" name="current_collection_id" id="current_collection_id"/>
                <button type="submit" id="setting_btn" class="popup__ok-btn" style="max-width: 100px;pointer-events:none;">Move</button>
            </div>
            </form>
        </div>
    </div>

    <div id="invite-property" class="mfp-hide popup popup--460">
        <div class="popup__inner">
            <div class="popup__title">
                Invite People
            </div>
            <form method="POST" action="{{ url('/property/send/invitation') }}">
            {{ csrf_field() }}
            <div class="popup-form__row" style="padding: 20px 0 0 0;">
                <div class="search-add" style="justify-content: center;">
                    <input class="search-add__field" type="text" name="email" onchange="return checkEmail(this.value);" placeholder="Type email to send invitation" style="text-align: center;border-radius:4px;border-right: 1px solid #B1B1B1;">
                    <!--<button class="search-add__btn" type="button" style="text-transform: none;">Add</button>-->
                </div>
                <div class="list-checks">

                </div>
            </div>
            <div class="popup-form__btns" style="justify-content: center;">
                <input type="hidden" name="property_id" id="property_id"/>
                <input type="hidden" name="collection_id" id="collection_id"/>
                <button type="submit" id="invite_btn" class="popup__ok-btn" style="max-width: 100px;pointer-events:none;">Send</button>
            </div>
            </form>
        </div>
    </div>

    <script>

        function checkboxLength()
        {
            if ($("#remove_users input:checkbox:checked").length > 0)
            {
                $("#remove_user_icon").show();
            }
            else
            {
                $("#remove_user_icon").hide();
            }
        }

        $('.invite_property_popup').magnificPopup({
            type: 'inline',
            midClick: true
        });

        $('.collection_setting_popup').magnificPopup({
            type: 'inline',
            midClick: true
        });

        /*$('.collection_chat_popup').magnificPopup({
            type: 'inline',
            midClick: true
        });*/

        $(".invite_property_popup").on("click", function(){
            var property_id = $(this).data("id");
            $("#property_id").val(property_id);
            var collection_id = $(".collection-nav__item.active").data("id");
            $("#collection_id").val(collection_id);
        });

        $(".collection_setting_popup").on("click", function(){
            var property_id = $(this).data("id");
            $("#propertyid").val(property_id);
            var collection_id = $(".collection-nav__item.active").data("id");
            $("#current_collection_id").val(collection_id);
        });

        $(".collection_chat_popup").on("click", function(){
            var property_id = $(this).data("id");
            $(".chat_window").hide();
            $("#property_id_active").val(property_id);
            $("#chat_window_"+property_id).show();
            $('#message_'+property_id).focus();
            $.ajax({
                url: "{{ url('/collection/property/chat') }}",
                type: 'post',
                async: false,
                data: {
                    "_token": '{{ csrf_token() }}',
                    "property_id" : property_id
                },
                success: function(response) {
                    if(response != '') {
                        $('#chat_messages_'+property_id).html(response);
                        var wtf    = $('#chat_messages_'+property_id);
                        var height = wtf[0].scrollHeight;
                        wtf.scrollTop(height);
                    }
                }
            });
        });

        function send_message(property_id)
        {
            var message = $('#message_'+property_id).val();
            if(message != '')
            {
                var chat_id = $('#chat_id').val();
                $.ajax({
                    url: "{{url('/collection/property/chat/send/message')}}",
                    type: "POST",
                    async: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        "message" : message,
                        "chat_id" : chat_id
                    },
                    success: function( response ) {
                        $('#chat_messages_'+property_id).html(response);
                        var wtf    = $('#chat_messages_'+property_id);
                        var height = wtf[0].scrollHeight;
                        wtf.scrollTop(height);
                        $('#message_'+property_id).val('');
                        $('#message_'+property_id).focus();
                    }
                });
            }
        }

        setInterval(updateChatWindow, 5000);

        function updateChatWindow()
        {
            var property_id = $("#property_id_active").val();
            var chat_id = $("#chat_id").val();
            if(chat_id > 0) {
                $.ajax({
                    url: "{{ url('/collection/property/chat/update') }}",
                    type: 'post',
                    async: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        "chat_id" : chat_id
                    },
                    success: function(response) {
                        if(response != '') {
                            $('#chat_messages_'+property_id).html(response);
                            var wtf    = $('#chat_messages_'+property_id);
                            var height = wtf[0].scrollHeight;
                            wtf.scrollTop(height);
                        }
                    }
                });
            }
        }

        $('.custom-select').select2({
            minimumResultsForSearch : Infinity,
            theme: 'custom'
        });

        function checkEmail(email) {
            var atpos = email.indexOf("@");
            var dotpos = email.lastIndexOf(".");
            if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length) {
                $('#invite_btn').css('pointer-events', 'none');
            } else {
                $('#invite_btn').css('pointer-events', '');
            }
            return true;
        }

        function checkCollection(collection_id) {
            if(collection_id > 0) {
                $('#setting_btn').css('pointer-events', '');
            } else {
                $('#setting_btn').css('pointer-events', 'none');
            }
        }

        function enter_message(e, property_id)
        {
            if (e.which == 13) {
                send_message(property_id);
                return false;
            }
        }
    </script>

<?php } else { ?><?php } ?>
