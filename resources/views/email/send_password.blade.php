<div class="card-body">
    <div class="d-flex flex-wrap gap-2 justify-content-between mb-8">
        <div class="d-flex align-items-center flex-wrap gap-2">
            <h2 class="fw-bold me-3 my-1">{{$subject}}</h2>
        </div>
    </div>
    <div data-kt-inbox-message="message_wrapper">
        <div class="collapse fade" data-kt-inbox-message="message">
            <div class="py-5">
                <p>Xin chào {{$content['full_name']}},</p>
                <p>Một tài khoản đã được tạo cho bạn tại: idigi.ismart.edu.vn
                    Vui lòng truy cập vào đường link này để đăng nhập với thông tin:</p>
                <p>Tên đăng nhập :{{$content['username']}}</p>
                <p class="mb-0">Mật khẩu :{{$content['password']}}</p>
                <p>Đây là email tự động, vui lòng không trả lời!</p>
                <p>Thanks and best regards!</p>
            </div>
        </div>
    </div>
</div>
