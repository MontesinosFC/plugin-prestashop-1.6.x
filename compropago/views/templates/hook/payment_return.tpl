{*
* Copyright 2015 Compropago.
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
*     http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*
* @author Rolando Lucio <rolando@compropago.com>
*
*}

{if $status == 'ok'}
	<p>{l s='Your order on %s is complete.' sprintf=$shop_name mod='compropago'}
		<br /><br />
		{l s='Your check must include:' mod='compropago'}
		<br /><br />- {l s='Payment amount.' mod='compropago'} <span class="price"><strong>{$total_to_pay}</strong></span>
		<br /><br />- {l s='Payable to the order of' mod='compropago'} <strong>{if $chequeName}{$chequeName}{else}___________{/if}</strong>
		<br /><br />- {l s='Mail to' mod='compropago'} <strong>{if $chequeAddress}{$chequeAddress}{else}___________{/if}</strong>
		{if !isset($reference)}
			<br /><br />- {l s='Do not forget to insert your order number #%d.' sprintf=$id_order mod='compropago'}
		{else}
			<br /><br />- {l s='Do not forget to insert your order reference %s.' sprintf=$reference mod='compropago'}
		{/if}
		<br /><br />{l s='An email has been sent to you with this information.' mod='compropago'}
		<br /><br /><strong>{l s='Your order will be sent as soon as we receive your payment.' mod='compropago'}</strong>
		<br /><br />{l s='For any questions or for further information, please contact our' mod='compropago'} <a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='customer service department.' mod='compropago'}</a>.
	</p>
{else}
	<p class="warning">
		{l s='We have noticed that there is a problem with your order. If you think this is an error, you can contact our' mod='compropago'} 
		<a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='customer service department.' mod='compropago'}</a>.
	</p>
{/if}
