@extends('tenant.frontend.frontend-page-master')

@section('title')
    {{__('Create Support Ticket')}}
@endsection

@section('page-title')
    {{__('Create Support Ticket')}}
@endsection

@section('content')

    <div class="PaymentArea section-padding">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-xl-8 col-lg-9">
                    <div class="applyDetails mb-24">
                        <div class="section-tittle text-center mb-30">
                            <h2 class="tittle">{{__('Create Ticket')}}</h2>
                        </div>
                        <form>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <label class="catTittle"> {{__('Ticket Subject')}}</label>
                                    <div class="input-form input-form2">
                                        <input type="text" placeholder="Enter your ticket subject">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <label class="catTittle"> {{__('Title')}}</label>
                                    <div class="input-form input-form2">
                                        <input type="email" placeholder="Enter your ticket Title">
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12">
                                    <label class="catTittle">{{__('Ticket Description ')}}</label>
                                    <div class="input-form input-form2">
                                        <textarea placeholder="Description"></textarea>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12">
                                    <label class="catTittle">{{__('Upload Image')}}</label>

                                    <div class="file_upload">
                                        <input type="file" name="file1" id="file1" class="input-file">
                                        <label for="file1" class="js-labelFile has-file">
                                            <span class="btn_file_upload">{{__('Choose File')}}</span>
                                            <span class="js-fileName"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="btn-wrapper mt-50">
                                        <button type="submit" class="cmn-btn4"> {{__('Submit a Ticket')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
