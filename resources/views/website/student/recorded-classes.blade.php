@extends('website.layout')

@section('title', $course->course_name . ' - Recorded Classes')

@push('styles')
<style>
    :root {
        --bg-deep: #0a0e17;
        --bg-panel: #11161f;
        --bg-card: #161c27;
        --bg-card-hover: #1c2330;
        --line: #232b39;
        --line-2: #2a3343;
        --txt-primary: #e6e9ef;
        --txt-secondary: #9aa3b2;
        --txt-muted: #5c6677;
        --accent: #f7931e;
        --accent-2: #ffbb55;
        --green: #22c55e;
        --blue: #3b82f6;
        --rose: #f43f5e;
    }

    body { background: var(--bg-deep); color: var(--txt-primary); }

    /* ===== NAVBAR DARK OVERRIDE ===== */
    .navbar.fixed-top {
        background: rgba(17, 22, 31, 0.92) !important;
        backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid #1c2330;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }
    .navbar .navbar-brand, .navbar .navbar-brand:hover { color: #f7931e !important; }
    .navbar-nav .nav-link, .navbar-nav .nav-link:focus { color: #c7ccd6 !important; }
    .navbar-nav .nav-link:hover, .navbar-nav .nav-link.active { color: #f7931e !important; }
    .navbar .btn-primary-custom {
        background: #f7931e !important; border-color: #f7931e !important; color: #fff !important;
        box-shadow: 0 4px 14px rgba(247, 147, 30, 0.3);
    }
    .navbar .btn-primary-custom:hover { background: #ffbb55 !important; border-color: #ffbb55 !important; }
    .navbar .dropdown-menu { background: #161c27; border: 1px solid #232b39; box-shadow: 0 12px 30px rgba(0,0,0,0.5); }
    .navbar .dropdown-item { color: #c7ccd6; }
    .navbar .dropdown-item:hover, .navbar .dropdown-item:focus { background: #1c2330; color: #f7931e; }
    .navbar .dropdown-item.text-danger { color: #fb7185 !important; }
    .navbar .dropdown-divider { border-top-color: #232b39; }
    .navbar-toggler { border-color: #232b39; padding: 4px 8px; }
    .navbar-toggler-icon { filter: invert(0.9); }

    /* ===== FOOTER DARK OVERRIDE ===== */
    .footer { background: #0a0e17 !important; color: #c7ccd6 !important; border-top: 1px solid #1c2330; }
    .footer h5 { color: #fff; font-weight: 700; }
    .footer p, .footer li, .footer span { color: #9aa3b2; }
    .footer a { color: #9aa3b2; text-decoration: none; }
    .footer a:hover { color: #f7931e; }
    .footer .contact-info li i { color: #f7931e !important; }
    .footer hr { background-color: #1c2330 !important; opacity: 1; }
    .footer .social-links a { background: rgba(247,147,30,0.1); color: #f7931e; border: 1px solid rgba(247,147,30,0.2); }
    .footer .social-links a:hover { background: #f7931e; color: #fff; border-color: #f7931e; }

    /* ===== PAGE SHELL ===== */
    .content-container {
        padding: 30px 0 60px;
        min-height: calc(100vh - 80px);
    }

    /* breadcrumb */
    .breadcrumb {
        background: transparent !important;
        padding: 0;
        margin-bottom: 18px;
        font-size: 0.9rem;
    }
    .breadcrumb-item a { color: var(--txt-secondary); text-decoration: none; }
    .breadcrumb-item a:hover { color: var(--accent); }
    .breadcrumb-item.active { color: var(--txt-primary); }
    .breadcrumb-item + .breadcrumb-item::before { color: var(--txt-muted); }

    /* ===== VIDEO PLAYER ===== */
    .video-player-section {
        background: #000;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 18px;
        border: 1px solid var(--line);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
    }
    .video-player-wrapper {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
    }
    .video-player-wrapper video {
        position: absolute; top: 0; left: 0;
        width: 100%; height: 100%;
    }

    /* video info card */
    .video-info-section {
        background: var(--bg-card);
        border: 1px solid var(--line);
        padding: 22px;
        border-radius: 18px;
        margin-bottom: 20px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.18);
    }
    .video-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--txt-primary);
        margin-bottom: 12px;
        letter-spacing: -0.3px;
    }
    .video-meta {
        color: var(--txt-secondary);
        font-size: 0.92rem;
        display: flex; flex-wrap: wrap; gap: 16px;
    }
    .video-meta i { color: var(--accent); margin-right: 5px; }
    .video-meta strong { color: var(--txt-primary); font-weight: 600; }
    .video-description {
        margin-top: 16px;
        padding-top: 14px;
        border-top: 1px solid var(--line);
        color: var(--txt-secondary);
        line-height: 1.65;
    }
    .video-description strong { color: var(--txt-primary); display: inline-flex; align-items: center; gap: 6px; }
    .video-description strong i { color: var(--accent); }
    .video-description p { margin: 8px 0 0; color: var(--txt-secondary); }

    /* ===== RECORDED CLASSES LIST ===== */
    .recorded-classes-list {
        background: var(--bg-card);
        border: 1px solid var(--line);
        border-radius: 18px;
        padding: 16px;
        max-height: 620px;
        overflow-y: auto;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.18);
    }
    .recorded-classes-list::-webkit-scrollbar { width: 6px; }
    .recorded-classes-list::-webkit-scrollbar-thumb { background: var(--line-2); border-radius: 999px; }
    .recorded-classes-list::-webkit-scrollbar-track { background: transparent; }

    .list-header {
        background: linear-gradient(135deg, var(--accent) 0%, #c0681a 100%);
        color: #fff;
        padding: 16px 18px;
        border-radius: 14px 14px 0 0;
        margin: -16px -16px 14px -16px;
        box-shadow: 0 4px 12px rgba(247, 147, 30, 0.25);
    }
    .list-header h5 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 700;
        display: flex; align-items: center; gap: 10px;
    }

    .class-item {
        padding: 14px;
        background: var(--bg-panel);
        border: 1px solid var(--line);
        border-radius: 12px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .class-item:hover {
        background: var(--bg-card-hover);
        border-color: var(--line-2);
        transform: translateX(3px);
    }
    .class-item.active {
        background: linear-gradient(135deg, rgba(247,147,30,0.15) 0%, rgba(247,147,30,0.05) 100%);
        border-color: rgba(247, 147, 30, 0.45);
        border-left: 4px solid var(--accent);
    }
    .class-item-title {
        font-weight: 600;
        color: var(--txt-primary);
        margin-bottom: 6px;
        font-size: 0.95rem;
        display: flex; align-items: center;
    }
    .class-item-title i { color: var(--accent); }
    .class-item.active .class-item-title { color: var(--accent-2); }
    .class-item-meta { font-size: 0.8rem; color: var(--txt-muted); display: flex; flex-wrap: wrap; gap: 10px; }
    .class-item-meta i { color: var(--accent); margin-right: 3px; }

    /* ===== EMPTY / PLACEHOLDER ===== */
    .no-video-placeholder {
        background: linear-gradient(135deg, var(--bg-panel) 0%, var(--bg-card) 100%);
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--txt-secondary);
        border-radius: 16px;
        border: 1px dashed var(--line-2);
    }
    .no-video-placeholder i { font-size: 3.5rem; color: var(--line-2); margin-bottom: 16px; }
    .no-video-placeholder h4 { color: var(--txt-primary); font-weight: 700; }
    .no-video-placeholder p { color: var(--txt-muted); margin: 0; }

    .no-classes-message {
        text-align: center;
        padding: 36px 16px;
        color: var(--txt-muted);
    }
    .no-classes-message i { font-size: 2.5rem; color: var(--line-2); margin-bottom: 14px; display: block; }
    .no-classes-message p { color: var(--txt-secondary); margin: 0; }

    /* fade-in */
    @keyframes fadeUp { from { opacity: 0; transform: translateY(8px);} to { opacity: 1; transform: translateY(0);} }
    .fade-in { animation: fadeUp 0.4s ease both; }

    @media (max-width: 768px) {
        .content-container { padding: 20px 0 40px; }
        .video-title { font-size: 1.15rem; }
        .recorded-classes-list { max-height: 460px; margin-top: 18px; }
        .video-info-section { padding: 16px; }
    }
</style>
@endpush

@section('content')
<div class="content-container">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">{{ $course->course_name }} - Recorded Classes</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Video Player Column (Left) -->
            <div class="col-lg-8 mb-4">
                <!-- Video Player -->
                <div class="video-player-section fade-in">
                    <div class="video-player-wrapper">
                        <div id="video_container">
                            @if(isset($recordedClasses) && count($recordedClasses) > 0)
                                <video id="video_player" controls controlsList="nodownload" disablePictureInPicture>
                                    <source src="{{ config('constants.recorded_class_video').$recordedClasses[0]->video_file }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @else
                                <div class="no-video-placeholder">
                                    <div class="text-center">
                                        <i class="fas fa-video"></i>
                                        <h4>No Recorded Classes Available</h4>
                                        <p>Please check back later</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Video Information -->
                @if(isset($recordedClasses) && count($recordedClasses) > 0)
                <div class="video-info-section">
                    <h3 class="video-title" id="video_title">{{ $recordedClasses[0]->title }}</h3>
                    <div class="video-meta">
                        <span><i class="fas fa-user"></i> <strong>Instructor:</strong> <span id="video_instructor">{{ $recordedClasses[0]->class_by }}</span></span>
                        @if($recordedClasses[0]->duration)
                        <span class="ms-3"><i class="fas fa-clock"></i> <strong>Duration:</strong> <span id="video_duration">{{ $recordedClasses[0]->duration }}</span></span>
                        @endif
                    </div>
                    @if($recordedClasses[0]->description)
                    <div class="video-description">
                        <strong><i class="fas fa-info-circle"></i> Description:</strong>
                        <p id="video_description">{{ $recordedClasses[0]->description }}</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Recorded Classes List Column (Right) -->
            <div class="col-lg-4">
                <div class="recorded-classes-list fade-in">
                    <div class="list-header">
                        <h5><i class="fas fa-play-circle"></i> Recorded Classes ({{ count($recordedClasses) }})</h5>
                    </div>

                    @if(isset($recordedClasses) && count($recordedClasses) > 0)
                        <div id="classes_list">
                            @foreach($recordedClasses as $index => $class)
                            <div class="class-item {{ $index == 0 ? 'active' : '' }}"
                                 onclick="playRecordedClass({{ $index }})"
                                 data-video-url="{{ config('constants.recorded_class_video').$class->video_file }}"
                                 data-title="{{ $class->title }}"
                                 data-instructor="{{ $class->class_by }}"
                                 data-duration="{{ $class->duration ?? 'N/A' }}"
                                 data-description="{{ $class->description ?? '' }}">
                                <div class="class-item-title">
                                    <i class="fas fa-play-circle me-2"></i>{{ $class->title }}
                                </div>
                                <div class="class-item-meta">
                                    <i class="fas fa-user"></i> {{ $class->class_by }}
                                    @if($class->duration)
                                    <span class="ms-2"><i class="fas fa-clock"></i> {{ $class->duration }}</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="no-classes-message">
                            <i class="fas fa-play-circle"></i>
                            <p>No recorded classes available for this course</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function playRecordedClass(index) {
        const classItems = document.querySelectorAll('.class-item');
        const clickedClass = classItems[index];

        // Remove active class from all items
        classItems.forEach(item => item.classList.remove('active'));

        // Add active class to clicked item
        clickedClass.classList.add('active');

        // Get data from clicked item
        const videoUrl = clickedClass.dataset.videoUrl;
        const title = clickedClass.dataset.title;
        const instructor = clickedClass.dataset.instructor;
        const duration = clickedClass.dataset.duration;
        const description = clickedClass.dataset.description;

        // Update video player
        const videoPlayer = document.getElementById('video_player');
        if (videoPlayer) {
            videoPlayer.src = videoUrl;
            videoPlayer.load();
            videoPlayer.play();
        }

        // Update video information
        document.getElementById('video_title').textContent = title;
        document.getElementById('video_instructor').textContent = instructor;
        document.getElementById('video_duration').textContent = duration;

        if (description && document.getElementById('video_description')) {
            document.getElementById('video_description').textContent = description;
        }

        // Scroll to top on mobile
        if (window.innerWidth < 768) {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
</script>
@endpush
