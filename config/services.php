<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Third Party Services
  |--------------------------------------------------------------------------
  |
  | This file is for storing the credentials for third party services such
  | as Mailgun, Postmark, AWS and more. This file provides the de facto
  | location for this type of information, allowing packages to have
  | a conventional file to locate the various service credentials.
  |
  */

  'postmark' => [
    'token' => env('POSTMARK_TOKEN'),
  ],

  'ses' => [
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
  ],

  'slack' => [
    'notifications' => [
      'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
      'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
    ],
  ],

  'openai' => [
    'api_key' => env('OPENAI_API_KEY'),
    'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
  ],

  'groq' => [
    'key' => env('GROQ_API_KEY'),
    'model' => env('GROQ_EMBEDDING_MODEL', 'text-embedding-ada-002'),
    'base_url' => env('GROQ_BASE_URL', 'https://api.groq.com/openai/v1'),
  ],

  'huggingface' => [
    'api_key' => env('HUGGINGFACE_API_KEY'),
    'embedding_model' => env('HUGGINGFACE_EMBEDDING_MODEL', 'sentence-transformers/all-MiniLM-L6-v2'),
    'base_url' => env('HUGGINGFACE_BASE_URL', 'https://api-inference.huggingface.co'),
  ],

  'gemini' => [
    'api_key' => env('GEMINI_API_KEY'),
    'base_url' => env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta'),
    'model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
  ],

  'groq' => [
    'api_key' => env('GROQ_API_KEY'),
    // Groq exposes OpenAI-compatible endpoints under /openai, but also has v1
    'base_url' => env('GROQ_BASE_URL', 'https://api.groq.com/openai/v1'),
    // Default model; can be overridden per call. Good options: llama-3.1-8b-instant, llama-3.1-70b-versatile
    'model' => env('GROQ_MODEL', 'llama-3.1-8b-instant'),
  ],

];

