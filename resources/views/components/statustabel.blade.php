<div class="status-timeline {{ strtolower($status) }}">
    <div class="status-dot">C</div>
    <div class="status-connector"></div>
    <div class="status-dot">S</div>
</div>

<style>
    .status-timeline {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .status-dot {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: bold;
    }
    .status-connector {
        height: 3px;
        width: 28px;
    }
    
    .status-timeline.created .status-dot:nth-child(1) {
        background-color: #007bff;
        color: white;
    }
    .status-timeline.created .status-connector {
        background-color: #dee2e6;
    }
    .status-timeline.created .status-dot:nth-child(3) {
        background-color: #e9ecef;
        color: #adb5bd;
    }
    
    .status-timeline.sent .status-dot:nth-child(1) {
        background-color: #007bff;
        color: white;
    }
    .status-timeline.sent .status-connector {
        background-color: #28a745;
    }
    .status-timeline.sent .status-dot:nth-child(3) {
        background-color: #28a745;
        color: white;
    }
</style>