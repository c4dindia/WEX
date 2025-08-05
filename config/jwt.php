<?php

return [
    /*
    |--------------------------------------------------------------------------
    | JWT Authentication Secret
    |--------------------------------------------------------------------------
    |
    | Don't forget to set this in your .env file, as it will be used to sign
    | your tokens. A helper command is provided for this:
    | `php artisan jwt:secret`
    |
    | Note: This will be used for Symmetric algorithms only (HMAC),
    | since RSA and ECDSA use a private/public key combo (See below).
    |
    */

    'secret' => env('JWT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | JWT Authentication Keys
    |--------------------------------------------------------------------------
    |
    | The algorithm you are using, will determine whether your tokens are
    | signed with a random string (defined in `JWT_SECRET`) or using the
    | following public & private keys.
    |
    | Symmetric Algorithms:
    | HS256, HS384 & HS512 will use `JWT_SECRET`.
    |
    | Asymmetric Algorithms:
    | RS256, RS384 & RS512 / ES256, ES384 & ES512 will use the keys below.
    |
    */

    'keys' => [
        /*
        |--------------------------------------------------------------------------
        | Public Key
        |--------------------------------------------------------------------------
        |
        | A path or resource to your public key.
        |
        | E.g. 'file://path/to/public/key'
        |
        */

        // 'public' => env('JWT_PUBLIC_KEY'),
        'public' => "-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAudKTGqRaY8enGritbh9I
owIvJKvgnbYaaqwUNjcZF6YlcdQJcWnoHKIlrkY2hjORgPcKKFqJkWNdkkd1VlVu
FAJ2FlUXCFQCsfSh/3V+uIi4Kj5xXm7uhdlD5m4rsV1cG0oo2iVlccC8IUrgyHMA
e5epwyXkV21cyIFlKkFH8J3RdmyD6JwAwtiaxg8vJipXoQ7FXBkQ/QK2jUrNiI9R
W+b10m0JvcVJnv5IgX1lZjkl/jtveeMEC9B1eQgltHchQ8yWeq5oM4aSZHPNo+TX
4lje6r9ZeoNu56NXc4xJN7MZzsIV9hqHijVyO7enSftR+M/NYI6yVaJ/ccDr7a1C
VHdWFXjBGxdFhRyzztg98NodAv7WU2Xf2dh9cXfd+70fJvdXpYXLzzt+PZkMgMDw
ic3iGWo0/rEld9Lg9uTmTenkIcXiT16L2zryXeXwwHbwqE1A4B9kQW9X3qBRSeEw
2YBpijRiw9E9C+kZhh7Rw9Vk5kbJzOhcRFaRb0pfINwe/iZjLcZxn1uI7Go9lDnH
LxAzd4TWa3TWjl3sXn9BlsbTjeJhGacbM5X0rnuFptl0MTKQOheMrOHUusPEYpLv
gl5JXg6/5yKQFm5E4+guwpTyFmZUKKzQhnH6jD7GRie+fjGwbavFYAMbgcpVnU2/
0GjuSbIvwhnD/ivGyhbzbY8CAwEAAQ==
-----END PUBLIC KEY-----
",

        /*
        |--------------------------------------------------------------------------
        | Private Key
        |--------------------------------------------------------------------------
        |
        | A path or resource to your private key.
        |
        | E.g. 'file://path/to/private/key'
        |
        */

        // 'private' => env('JWT_PRIVATE_KEY'),
        'private' => '-----BEGIN PRIVATE KEY-----
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
t9fvtI/nfNm1VJYzGZov5pjDTbnsFQ==
-----END PRIVATE KEY-----
',

        /*
        |--------------------------------------------------------------------------
        | Passphrase
        |--------------------------------------------------------------------------
        |
        | The passphrase for your private key. Can be null if none set.
        |
        */

        'passphrase' => env('JWT_PASSPHRASE'),
    ],

    /*
    |--------------------------------------------------------------------------
    | JWT time to live
    |--------------------------------------------------------------------------
    |
    | Specify the length of time (in minutes) that the token will be valid for.
    | Defaults to 1 hour.
    |
    | You can also set this to null, to yield a never expiring token.
    | Some people may want this behaviour for e.g. a mobile app.
    | This is not particularly recommended, so make sure you have appropriate
    | systems in place to revoke the token if necessary.
    | Notice: If you set this to null you should remove 'exp' element from 'required_claims' list.
    |
    */

    'ttl' => env('JWT_TTL', 60),

    /*
    |--------------------------------------------------------------------------
    | Refresh time to live
    |--------------------------------------------------------------------------
    |
    | Specify the length of time (in minutes) that the token can be refreshed
    | within. I.E. The user can refresh their token within a 2 week window of
    | the original token being created until they must re-authenticate.
    | Defaults to 2 weeks.
    |
    | You can also set this to null, to yield an infinite refresh time.
    | Some may want this instead of never expiring tokens for e.g. a mobile app.
    | This is not particularly recommended, so make sure you have appropriate
    | systems in place to revoke the token if necessary.
    |
    */

    'refresh_ttl' => env('JWT_REFRESH_TTL', 20160),

    /*
    |--------------------------------------------------------------------------
    | JWT hashing algorithm
    |--------------------------------------------------------------------------
    |
    | Specify the hashing algorithm that will be used to sign the token.
    |
    | See here: https://github.com/namshi/jose/tree/master/src/Namshi/JOSE/Signer/OpenSSL
    | for possible values.
    |
    */

    // 'algo' => env('JWT_ALGO', 'HS256'),
    'algo' => 'RS256',

    /*
    |--------------------------------------------------------------------------
    | Required Claims
    |--------------------------------------------------------------------------
    |
    | Specify the required claims that must exist in any token.
    | A TokenInvalidException will be thrown if any of these claims are not
    | present in the payload.
    |
    */

    'required_claims' => [
        'iss',
        'iat',
        'exp',
        'nbf',
        'sub',
        'jti',
    ],

    /*
    |--------------------------------------------------------------------------
    | Persistent Claims
    |--------------------------------------------------------------------------
    |
    | Specify the claim keys to be persisted when refreshing a token.
    | `sub` and `iat` will automatically be persisted, in
    | addition to the these claims.
    |
    | Note: If a claim does not exist then it will be ignored.
    |
    */

    'persistent_claims' => [
        // 'foo',
        // 'bar',
    ],

    /*
    |--------------------------------------------------------------------------
    | Lock Subject
    |--------------------------------------------------------------------------
    |
    | This will determine whether a `prv` claim is automatically added to
    | the token. The purpose of this is to ensure that if you have multiple
    | authentication models e.g. `App\User` & `App\OtherPerson`, then we
    | should prevent one authentication request from impersonating another,
    | if 2 tokens happen to have the same id across the 2 different models.
    |
    | Under specific circumstances, you may want to disable this behaviour
    | e.g. if you only have one authentication model, then you would save
    | a little on token size.
    |
    */

    'lock_subject' => true,

    /*
    |--------------------------------------------------------------------------
    | Leeway
    |--------------------------------------------------------------------------
    |
    | This property gives the jwt timestamp claims some "leeway".
    | Meaning that if you have any unavoidable slight clock skew on
    | any of your servers then this will afford you some level of cushioning.
    |
    | This applies to the claims `iat`, `nbf` and `exp`.
    |
    | Specify in seconds - only if you know you need it.
    |
    */

    'leeway' => env('JWT_LEEWAY', 0),

    /*
    |--------------------------------------------------------------------------
    | Blacklist Enabled
    |--------------------------------------------------------------------------
    |
    | In order to invalidate tokens, you must have the blacklist enabled.
    | If you do not want or need this functionality, then set this to false.
    |
    */

    'blacklist_enabled' => env('JWT_BLACKLIST_ENABLED', true),

    /*
    | -------------------------------------------------------------------------
    | Blacklist Grace Period
    | -------------------------------------------------------------------------
    |
    | When multiple concurrent requests are made with the same JWT,
    | it is possible that some of them fail, due to token regeneration
    | on every request.
    |
    | Set grace period in seconds to prevent parallel request failure.
    |
    */

    'blacklist_grace_period' => env('JWT_BLACKLIST_GRACE_PERIOD', 0),

    /*
    |--------------------------------------------------------------------------
    | Show blacklisted token option
    |--------------------------------------------------------------------------
    |
    | Specify if you want to show black listed token exception on the laravel logs.
    |
    */

    'show_black_list_exception' => env('JWT_SHOW_BLACKLIST_EXCEPTION', true),

    /*
    |--------------------------------------------------------------------------
    | Cookies encryption
    |--------------------------------------------------------------------------
    |
    | By default Laravel encrypt cookies for security reason.
    | If you decide to not decrypt cookies, you will have to configure Laravel
    | to not encrypt your cookie token by adding its name into the $except
    | array available in the middleware "EncryptCookies" provided by Laravel.
    | see https://laravel.com/docs/master/responses#cookies-and-encryption
    | for details.
    |
    | Set it to true if you want to decrypt cookies.
    |
    */

    'decrypt_cookies' => false,

    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    |
    | Specify the various providers used throughout the package.
    |
    */

    'providers' => [
        /*
        |--------------------------------------------------------------------------
        | JWT Provider
        |--------------------------------------------------------------------------
        |
        | Specify the provider that is used to create and decode the tokens.
        |
        */

        'jwt' => PHPOpenSourceSaver\JWTAuth\Providers\JWT\Lcobucci::class,

        /*
        |--------------------------------------------------------------------------
        | Authentication Provider
        |--------------------------------------------------------------------------
        |
        | Specify the provider that is used to authenticate users.
        |
        */

        'auth' => PHPOpenSourceSaver\JWTAuth\Providers\Auth\Illuminate::class,

        /*
        |--------------------------------------------------------------------------
        | Storage Provider
        |--------------------------------------------------------------------------
        |
        | Specify the provider that is used to store tokens in the blacklist.
        |
        */

        'storage' => PHPOpenSourceSaver\JWTAuth\Providers\Storage\Illuminate::class,
    ],
];
