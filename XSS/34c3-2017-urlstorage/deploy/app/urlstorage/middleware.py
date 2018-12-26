class SecHeadersMiddleware:
    def __init__(self, get_response):
        self.get_response = get_response

    def __call__(self, request):
        response = self.get_response(request)
        response['X-Frame-Options'] = "DENY"
        response['Referrer-Policy'] = "no-referrer"
        response['X-Content-Type-Options'] = "no-sniff"
        response['X-XSS-Protection'] = "1; mode=block"
        response['Content-Security-Policy'] = (
            "frame-ancestors 'none'; "
            "form-action 'self'; "
            "connect-src 'self'; "
            "script-src 'self'; "
            "font-src 'self' ; "
            "style-src 'self'; "
            )
        return response

