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
import LeafletController from '../src/leaflet_controller';

// Controller used to check the actual controller was properly booted
class CheckController extends Controller {
    connect() {
        this.element.addEventListener('leaflet:pre-connect', (event) => {
            this.element.classList.add('pre-connected');
        });
        
        this.element.addEventListener('leaflet:connect', (event) => {
            this.element.classList.add('connected');
        });
    }
}

const startStimulus = () => {
    const application = Application.start();
    application.register('check', CheckController);
    application.register('leaflet', LeafletController);
};

describe('LeafletController', () => {
    let container;
    
    beforeEach(() => {
        container = mountDOM(`
          <div 
              data-testid="map"
              data-controller="check leaflet" 
              data-leaflet-view-value="&#x7B;&quot;center&quot;&#x3A;&#x7B;&quot;lat&quot;&#x3A;46.903354,&quot;lng&quot;&#x3A;1.888334&#x7D;,&quot;zoom&quot;&#x3A;6,&quot;tileLayer&quot;&#x3A;&#x7B;&quot;url&quot;&#x3A;&quot;https&#x3A;&#x5C;&#x2F;&#x5C;&#x2F;tile.openstreetmap.org&#x5C;&#x2F;&#x7B;z&#x7D;&#x5C;&#x2F;&#x7B;x&#x7D;&#x5C;&#x2F;&#x7B;y&#x7D;.png&quot;,&quot;attribution&quot;&#x3A;&quot;&#x5C;u00a9&#x20;&lt;a&#x20;href&#x3D;&#x5C;&quot;https&#x3A;&#x5C;&#x2F;&#x5C;&#x2F;www.openstreetmap.org&#x5C;&#x2F;copyright&#x5C;&quot;&gt;OpenStreetMap&lt;&#x5C;&#x2F;a&gt;&quot;&#x7D;,&quot;fitBoundsToMarkers&quot;&#x3A;false,&quot;markers&quot;&#x3A;&#x5B;&#x7B;&quot;_id&quot;&#x3A;712,&quot;position&quot;&#x3A;&#x7B;&quot;lat&quot;&#x3A;48.8566,&quot;lng&quot;&#x3A;2.3522&#x7D;,&quot;title&quot;&#x3A;&quot;Paris&quot;,&quot;riseOnHover&quot;&#x3A;false,&quot;riseOffset&quot;&#x3A;250,&quot;draggable&quot;&#x3A;false&#x7D;,&#x7B;&quot;_id&quot;&#x3A;714,&quot;position&quot;&#x3A;&#x7B;&quot;lat&quot;&#x3A;45.764,&quot;lng&quot;&#x3A;4.8357&#x7D;,&quot;title&quot;&#x3A;&quot;Lyon&quot;,&quot;riseOnHover&quot;&#x3A;false,&quot;riseOffset&quot;&#x3A;250,&quot;draggable&quot;&#x3A;false&#x7D;,&#x7B;&quot;_id&quot;&#x3A;716,&quot;position&quot;&#x3A;&#x7B;&quot;lat&quot;&#x3A;43.2965,&quot;lng&quot;&#x3A;5.3698&#x7D;,&quot;title&quot;&#x3A;&quot;Marseille&quot;,&quot;riseOnHover&quot;&#x3A;false,&quot;riseOffset&quot;&#x3A;250,&quot;draggable&quot;&#x3A;false&#x7D;,&#x7B;&quot;_id&quot;&#x3A;718,&quot;position&quot;&#x3A;&#x7B;&quot;lat&quot;&#x3A;43.7102,&quot;lng&quot;&#x3A;7.262&#x7D;,&quot;title&quot;&#x3A;&quot;Nice&quot;,&quot;riseOnHover&quot;&#x3A;false,&quot;riseOffset&quot;&#x3A;250,&quot;draggable&quot;&#x3A;false&#x7D;,&#x7B;&quot;_id&quot;&#x3A;720,&quot;position&quot;&#x3A;&#x7B;&quot;lat&quot;&#x3A;47.2184,&quot;lng&quot;&#x3A;-1.5536&#x7D;,&quot;title&quot;&#x3A;&quot;Nantes&quot;,&quot;riseOnHover&quot;&#x3A;false,&quot;riseOffset&quot;&#x3A;250,&quot;draggable&quot;&#x3A;false&#x7D;,&#x7B;&quot;_id&quot;&#x3A;722,&quot;position&quot;&#x3A;&#x7B;&quot;lat&quot;&#x3A;48.5734,&quot;lng&quot;&#x3A;7.7521&#x7D;,&quot;title&quot;&#x3A;&quot;Strasbourg&quot;,&quot;riseOnHover&quot;&#x3A;false,&quot;riseOffset&quot;&#x3A;250,&quot;draggable&quot;&#x3A;false&#x7D;&#x5D;,&quot;popups&quot;&#x3A;&#x5B;&#x7B;&quot;_markerId&quot;&#x3A;712,&quot;content&quot;&#x3A;&quot;&lt;b&gt;Paris&lt;&#x5C;&#x2F;b&gt;,&#x20;capitale&#x20;de&#x20;la&#x20;France,&#x20;est&#x20;une&#x20;grande&#x20;ville&#x20;europ&#x5C;u00e9enne&#x20;et&#x20;un&#x20;centre&#x20;mondial&#x20;de&#x20;l&#x27;art,&#x20;de&#x20;la&#x20;mode,&#x20;de&#x20;la&#x20;gastronomie&#x20;et&#x20;de&#x20;la&#x20;culture.&quot;,&quot;position&quot;&#x3A;&#x7B;&quot;lat&quot;&#x3A;48.8566,&quot;lng&quot;&#x3A;2.3522&#x7D;,&quot;opened&quot;&#x3A;false,&quot;autoClose&quot;&#x3A;true&#x7D;,&#x7B;&quot;_markerId&quot;&#x3A;714,&quot;content&quot;&#x3A;&quot;&lt;b&gt;Lyon&lt;&#x5C;&#x2F;b&gt;,&#x20;ville&#x20;fran&#x5C;u00e7aise&#x20;de&#x20;la&#x20;r&#x5C;u00e9gion&#x20;historique&#x20;Rh&#x5C;u00f4ne-Alpes,&#x20;se&#x20;trouve&#x20;&#x5C;u00e0&#x20;la&#x20;jonction&#x20;du&#x20;Rh&#x5C;u00f4ne&#x20;et&#x20;de&#x20;la&#x20;Sa&#x5C;u00f4ne.&quot;,&quot;position&quot;&#x3A;&#x7B;&quot;lat&quot;&#x3A;45.764,&quot;lng&quot;&#x3A;4.8357&#x7D;,&quot;opened&quot;&#x3A;false,&quot;autoClose&quot;&#x3A;true&#x7D;,&#x7B;&quot;_markerId&quot;&#x3A;716,&quot;content&quot;&#x3A;&quot;&lt;b&gt;Marseille&lt;&#x5C;&#x2F;b&gt;,&#x20;ville&#x20;portuaire&#x20;du&#x20;sud&#x20;de&#x20;la&#x20;France,&#x20;est&#x20;une&#x20;ville&#x20;cosmopolite&#x20;qui&#x20;a&#x20;&#x5C;u00e9t&#x5C;u00e9&#x20;un&#x20;centre&#x20;d&#x27;&#x5C;u00e9changes&#x20;commerciaux&#x20;et&#x20;culturels&#x20;depuis&#x20;sa&#x20;fondation&#x20;par&#x20;les&#x20;Grecs&#x20;vers&#x20;600&#x20;av.&#x20;J.-C.&quot;,&quot;position&quot;&#x3A;&#x7B;&quot;lat&quot;&#x3A;43.2965,&quot;lng&quot;&#x3A;5.3698&#x7D;,&quot;opened&quot;&#x3A;false,&quot;autoClose&quot;&#x3A;true&#x7D;,&#x7B;&quot;_markerId&quot;&#x3A;null,&quot;content&quot;&#x3A;&quot;Strasbourg&quot;,&quot;position&quot;&#x3A;&#x7B;&quot;lat&quot;&#x3A;48.5734,&quot;lng&quot;&#x3A;7.7521&#x7D;,&quot;opened&quot;&#x3A;true,&quot;autoClose&quot;&#x3A;true&#x7D;&#x5D;&#x7D;" style="height&#x3A;&#x20;700px&#x3B;&#x20;width&#x3A;&#x20;1024px&#x3B;&#x20;margin&#x3A;&#x20;10px"
          ></div>
        `);
    });
    
    afterEach(() => {
        clearDOM();
    });
    
    it('connect' , async () => {
        window.__symfony_ux_maps = {
            providers: {
                leaflet: {},
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
