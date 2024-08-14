<?php
declare(strict_types=1);
namespace App\Http\Middleware\Tenant;

use Closure;
use Illuminate\Http\Request;

use Stancl\Tenancy\Contracts\Tenant;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedByRequestDataException;
use Stancl\Tenancy\Middleware\IdentificationMiddleware;
use Stancl\Tenancy\Resolvers\DomainTenantResolver;
use Stancl\Tenancy\Tenancy;

class InitializeTenancyByDomainCustomisedMiddleware extends IdentificationMiddleware
{
    /** @var callable|null */
    public static $onFail;

    /** @var Tenancy */
    protected $tenancy;

    /** @var DomainTenantResolver */
    protected $resolver;

    public function __construct(Tenancy $tenancy, DomainTenantResolver $resolver)
    {
        $this->tenancy = $tenancy;
        $this->resolver = $resolver;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $eploded_url = explode(".",$request->getHost());
        $remove_unwanted_string_from_domain_url = $request->getHost();
        if(current($eploded_url) === "www"){
            $remove_unwanted_string_from_domain_url = substr(implode(".",$eploded_url),4);
        }

        return $this->initializeTenancy(
            $request, $next, $remove_unwanted_string_from_domain_url
        );
    }
}
