/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Application, Controller } from '@hotwired/stimulus';
import { getByTestId, waitFor } from '@testing-library/dom';
import { clearDOM, mountDOM } from '@symfony/stimulus-testing';
import GoogleMapsController from '../src/google_maps_controller';

// Controller used to check the actual controller was properly booted
class CheckController extends Controller {
    connect() {
        this.element.addEventListener('google-maps:pre-connect', (event) => {
            this.element.classList.add('pre-connected');
        });
        
        this.element.addEventListener('google-maps:connect', (event) => {
            this.element.classList.add('connected');
        });
    }
}

const startStimulus = () => {
    const application = Application.start();
    application.register('check', CheckController);
    application.register('google-maps', GoogleMapsController);
};

describe('GoogleMapsController', () => {
    let container;
    
    beforeEach(() => {
        container = mountDOM(`
          <div 
              data-testid="map"
              data-controller="check google-maps" 
              data-google-maps-view-value="&#x7B;&quot;mapId&quot;&#x3A;null,&quot;center&quot;&#x3A;null,&quot;zoom&quot;&#x3A;null,&quot;gestureHandling&quot;&#x3A;&quot;auto&quot;,&quot;backgroundColor&quot;&#x3A;null,&quot;disableDoubleClickZoom&quot;&#x3A;false,&quot;zoomControl&quot;&#x3A;true,&quot;zoomControlOptions&quot;&#x3A;&#x7B;&quot;position&quot;&#x3A;22&#x7D;,&quot;mapTypeControl&quot;&#x3A;true,&quot;mapTypeControlOptions&quot;&#x3A;&#x7B;&quot;mapTypeIds&quot;&#x3A;&#x5B;&#x5D;,&quot;position&quot;&#x3A;14,&quot;style&quot;&#x3A;0&#x7D;,&quot;streetViewControl&quot;&#x3A;true,&quot;streetViewControlOptions&quot;&#x3A;&#x7B;&quot;position&quot;&#x3A;22&#x7D;,&quot;fullscreenControl&quot;&#x3A;true,&quot;fullscreenControlOptions&quot;&#x3A;&#x7B;&quot;position&quot;&#x3A;20&#x7D;,&quot;fitBoundsToMarkers&quot;&#x3A;false,&quot;markers&quot;&#x3A;&#x5B;&#x5D;,&quot;infoWindows&quot;&#x3A;&#x5B;&#x5D;&#x7D;"
          ></div>
        `);
    });
    
    afterEach(() => {
        clearDOM();
    });
    
    it('connect' , async () => {
        window.__symfony_ux_maps = {
            providers: {
                google_maps: {
                    key: '',
                },
            },
        }
        
        const div = getByTestId(container, 'map');
        expect(div).not.toHaveClass('pre-connected');
        expect(div).not.toHaveClass('connected');

        startStimulus();
        await waitFor(() => expect(div).toHaveClass('pre-connected'));
        await waitFor(() => expect(div).toHaveClass('connected'));
    });
});
