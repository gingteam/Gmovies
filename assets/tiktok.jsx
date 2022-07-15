// @ts-check
import React, { useState } from 'react';
import { createRoot } from 'react-dom/client';

function App() {
  const [input, setInput] = useState('');
  const [watermark, setWatermark] = useState(false);
  const [isLoading, setLoading] = useState(false);
  const [notification, setNotification] = useState('');
  const [error, setError] = useState(true);

  const handleChange = () => {
    setLoading(true);
    fetch('/api/tiktok', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        url: input,
        watermark,
      }),
    }).then((res) => res.json()).then(
      /** @param {{success:boolean,result:string}} data */
      (data) => {
        setLoading(false);
        setError(!data.success);
        setNotification(data.success ? 'Success' : 'Failure, try again');
        setTimeout(() => {
          if (data.success) {
            window.location.href = data.result;
          }
          setNotification('');
        }, 2000);
      },
    );
  };

  return (
    <div className="container is-fluid">
      <div className="has-text-centered is-size-1">
        <i className="fa-brands fa-tiktok" />
      </div>
      {notification && <div className={`notification ${error ? 'is-danger' : 'is-success'}`}>{notification}</div>}
      <div className="card">
        <div className="card-header">
          <button type="button" className="card-header-icon" aria-label="more options">
            <span className="icon">
              <i className="fa-solid fa-circle" style={{ color: '#ff5f56' }} />
            </span>
            <span className="icon">
              <i className="fa-solid fa-circle" style={{ color: '#ffbd2e' }} />
            </span>
            <span className="icon">
              <i className="fa-solid fa-circle" style={{ color: '#27c93f' }} />
            </span>
          </button>
          <p className="card-header-title">Get link TikTok</p>
        </div>
        <div className="card-content">
          <div className="field">
            <div className="control has-icons-left">
              <input id="input" className="input" type="text" value={input} onChange={(e) => setInput(e.target.value)} />
              <span className="icon is-small is-left">
                <i className="fa-solid fa-link" />
              </span>
            </div>
          </div>
          <div className="field">
            <div className="control">
              <label htmlFor="watermark" className="checkbox">
                <input id="watermark" type="checkbox" defaultChecked={watermark} onChange={() => setWatermark(!watermark)} />
                {' Remove watermark'}
              </label>
            </div>
          </div>
          <div className="field">
            <div className="control">
              <button
                type="submit"
                className={`button is-success is-outlined${isLoading ? ' is-loading' : ''}`}
                onClick={() => handleChange()}
              >
                <span className="icon is-small">
                  <i className="fa-solid fa-floppy-disk" />
                </span>
                <span>Download</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

// @ts-ignore
createRoot(document.getElementById('root')).render(<App />);
